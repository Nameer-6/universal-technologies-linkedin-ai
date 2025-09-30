<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ScheduledPost;
use App\Models\LinkedinProfile;
use App\Models\LinkedinPostMetric;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\PersonaDataSetController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\Client\ConnectionException; // <— for retry-on-DNS/connect

class LinkedInController extends Controller
{
    // ===================== OpenAI config =====================
    protected $openai_api_key;
    protected $openai_api_base;

    public function __construct()
    {
        $this->openai_api_key = env('OPENAI_API_KEY');
        $this->openai_api_base = env('OPENAI_API_BASE', 'https://api.openai.com/v1');
    }

    // ===================== News fetcher config =====================
    protected string $newsUserAgent = 'Mozilla/5.0 (compatible; LaravelNewsFetcher/1.1)';
    protected int $newsTimeout = 8;             // was 10 — slightly tighter to avoid slow hosts
    protected int $feedCacheTtl = 180;          // cache raw RSS bodies a bit longer
    protected int $badFeedCooldownMin = 30;     // skip flaky hosts for N minutes

    // ===================== HTML helpers =====================
    protected function transformHTMLToFancyUnicode($html)
    {
        $html = preg_replace('/<div[^>]*>/', '<p>', $html);
        $html = str_replace('</div>', '</p>', $html);
        $html = preg_replace('/<p[^>]*>/', '', $html);
        $html = str_replace('</p>', "\n\n", $html);
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);

        $html = preg_replace_callback('/<strong>(.*?)<\/strong>/s', function ($m) {
            return $this->toUnicodeBold(strip_tags($m[1]));
        }, $html);
        $html = preg_replace_callback('/<em>(.*?)<\/em>/s', function ($m) {
            return $this->toUnicodeItalic(strip_tags($m[1]));
        }, $html);

        return trim(strip_tags($html, '<img><br><video><embed>'));
    }

    protected function transformMarkdownToFancyUnicode($text)
    {
        $text = preg_replace_callback('/\*\*(.*?)\*\*/s', fn($m) => $this->toUnicodeBold($m[1]), $text);
        $text = preg_replace_callback('/\*(.*?)\*/s', fn($m) => $this->toUnicodeItalic($m[1]), $text);
        return $text;
    }

    // ===================== (Everything below is your original LinkedIn code) =====================
    protected function toUnicodeBold($str)
    {
        $offsetUpper = hexdec('1D400') - ord('A');
        $offsetLower = hexdec('1D41A') - ord('a');
        $boldStr = '';
        for ($i = 0; $i < mb_strlen($str); $i++) {
            $char = mb_substr($str, $i, 1);
            $code = ord($char);
            if ($code >= 65 && $code <= 90) {
                $boldStr .= mb_chr($code + $offsetUpper, 'UTF-8');
            } elseif ($code >= 97 && $code <= 122) {
                $boldStr .= mb_chr($code + $offsetLower, 'UTF-8');
            } else {
                $boldStr .= $char;
            }
        }
        return $boldStr;
    }

    protected function toUnicodeItalic($str)
    {
        $offsetUpper = hexdec('1D434') - ord('A');
        $offsetLower = hexdec('1D44E') - ord('a');
        $italicStr = '';
        for ($i = 0; $i < mb_strlen($str); $i++) {
            $char = mb_substr($str, $i, 1);
            $code = ord($char);
            if ($code >= 65 && $code <= 90) {
                $italicStr .= mb_chr($code + $offsetUpper, 'UTF-8');
            } elseif ($code >= 97 && $code <= 122) {
                $italicStr .= mb_chr($code + $offsetLower, 'UTF-8');
            } else {
                $italicStr .= $char;
            }
        }
        return $italicStr;
    }

    protected function translateText($text, $targetLang)
    {
        try {
            $languageMap = $this->getLanguageMap();
            $languageFull = $languageMap[$targetLang] ?? 'English';
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$this->openai_api_key}",
            ])
                ->timeout(30)
                ->post("{$this->openai_api_base}/chat/completions", [
                    'model'     => 'gpt-4o',
                    'temperature' => 0.5,
                    'messages'  => [
                        ['role' => 'system', 'content' => "You are a professional translator. Translate the following text to {$languageFull} accurately, preserving the meaning and tone."],
                        ['role' => 'user',   'content' => $text],
                    ],
                ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'] ?? $text;
            }
            Log::warning("translateText: Failed to translate to {$languageFull}");
            return $text;
        } catch (\Exception $e) {
            Log::error("translateText: Error: " . $e->getMessage());
            return $text;
        }
    }

    protected function getLanguageMap()
    {
        return [
            'en' => 'English',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'es' => 'Español',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ru' => 'Русский',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'sv' => 'Svenska',
            'tr' => 'Türkçe',
            'uk' => 'Українська',
            'cs' => 'Čeština',
            'ro' => 'Română',
            'el' => 'Ελληνικά',
            'da' => 'Dansk',
            'fi' => 'Suomi',
            'no' => 'Norsk',
            'hu' => 'Magyar',
            'zh' => '中文',
            'ja' => '日本語',
            'ko' => '한국어',
            'ar' => 'العربية',
            'hi' => 'हिन्दी',
            'bn' => 'বাংলা',
            'ur' => 'اردو',
            'fa' => 'فارسی',
            'id' => 'Bahasa Indonesia',
            'ms' => 'Bahasa Melayu',
            'th' => 'ไทย',
            'vi' => 'Tiếng Việt',
            'ta' => 'தமிழ்',
            'te' => 'తెలుగు',
            'ml' => 'മലയാളം',
            'mr' => 'मराठी',
            'pa' => 'ਪੰਜਾਬੀ',
            'gu' => 'ગુજરાતી',
            'kn' => 'ಕನ್ನಡ',
            'my' => 'မြန်မာ',
            'si' => 'සිංහල',
            'ne' => 'नेपाली',
            'he' => 'עברית',
            'sw' => 'Kiswahili',
            'yo' => 'Yorùbá',
            'ig' => 'Asụsụ Igbo',
            'am' => 'አማርኛ',
            'zu' => 'isiZulu',
            'ha' => 'Hausa',
            'qu' => 'Runa Simi (Quechua)',
            'gn' => "Avañe'ẽ (Guarani)",
            'mi' => 'Te reo Māori',
            'tl' => 'Tagalog',
            'jv' => 'Basa Jawa',
            'su' => 'Basa Sunda',
            'eo' => 'Esperanto',
        ];
    }

    protected function cleanPostContent($content)
    {
        $content = preg_replace('/^(?:\*?\s*(Hook|Insights|Hashtags|Requirements):)/im', '', $content);
        $content = str_replace(['```', '\boxed', '{', '}'], '', $content);
        $content = preg_replace('/^(?:text|markdown)\s*$/im', '', $content);
        $content = preg_replace('/^#\s*idiot.*$/im', '', $content);
        $content = preg_replace('/^VariationTag\s*=.*$/mi', '', $content);
        $content = preg_replace("/\r\n|\n|\r/", "<br>", $content);
        $content = preg_replace('/^(<br>\s*)+/', '', $content);
        $content = preg_replace('/(<br>\s*)+$/', '', $content);
        $content = preg_replace('/(<br>\s*){2,}/', '<br>', $content);
        return trim($content);
    }

    protected function optimizeForLinkedIn($content, $maxChar, $minChar, $language)
    {
        $content = preg_replace('/([.!?])(<br>)([A-Z])/', '$1<br>$3', $content);
        $content = preg_replace('/(<br>\s*){2,}/', '<br>', $content);
        $content = preg_replace('/\s+$/', '', $content);

        preg_match_all('/#[\p{L}\p{N}_]+/u', $content, $m);
        $hashtags = $m[0] ?? [];
        $content = preg_replace('/#[\p{L}\p{N}_]+\s*/u', '', $content);
        $content = rtrim($content);
        $content = preg_replace('/(<br>\s*)+$/', '', $content);
        if (count($hashtags) > 7) $hashtags = array_slice($hashtags, 0, 7);
        if (!empty($hashtags)) $content .= '<br>' . implode(' ', $hashtags);
        return $content;
    }

    protected function generatePost($news_text, $influencer = "", $charLimit = null, $field = null, $language = 'en')
    {
        $user =  Auth::user() ? \App\Models\User::find(Auth::id()) : null;
        if (!$user) return ['post' => null, 'error' => 'Unauthorized: No user found.', 'credits' => 0];
        if ($user->credits < 1) return ['post' => null, 'error' => 'No credits left. Please purchase or renew your subscription.', 'credits' => 0];

        static $personaCache = [];
        $personaKey = "{$influencer}_{$field}";
        if (!isset($personaCache[$personaKey])) {
            $personaCache[$personaKey] = Cache::remember("persona_sample_{$personaKey}", 3600, function () use ($influencer, $field) {
                return (new PersonaDataSetController())->getSamplePosts($influencer, $field);
            });
        }
        $sampleData = $personaCache[$personaKey];
        $personaName = $sampleData['name'];
        $example     = $sampleData['sample'];

        $news_text = trim(preg_replace([
            '/^```(?:text|markdown)?\s*/',
            '/\s*```$/',
            '/^markdown\s*$/im',
            '/^text\s*$/im'
        ], '', $news_text));

        $languageMap = $this->getLanguageMap();
        $languageFull = $languageMap[$language] ?? 'English';
        if ($language !== 'en') {
            $translated = $this->translateText($news_text, $language);
            $news_text = $translated ?: $news_text;
        }

        $charLimitFinal = $charLimit ?? $this->determineDynamicCharLimit($news_text, $language);
        $minChar        = max(0, $charLimitFinal - 200);

        $prompt = <<<EOD
Here's a STYLE EXAMPLE from **{$personaName}** (do **not** copy any wording):

---
{$example}
---

Now write a brand-new LinkedIn post entirely in **{$languageFull}**, including all text, headings, bullet points, questions, and hashtags, in that exact structural style, about:

"{$news_text}"

**Requirements:**
1. **Hook** (first sentence) must be **bolded** and in {$languageFull}.
2. **Every section heading** (3–5 words) must be **bolded** and in {$languageFull}.
3. **Any question** you include must be **bolded** and in {$languageFull}.
4. Include one bullet-point section (3–5 bullets) in {$languageFull}.
5. Leave one blank line between sections.
6. End with one **bolded question** related to the post in {$languageFull}, right before the hashtags.
7. End with exactly 7 relevant hashtags in {$languageFull}.
8. Keep total length **between {$minChar} and {$charLimitFinal} characters** (as close as possible to {$charLimitFinal} without exceeding).
9. Do **not** truncate; ensure the final question + hashtags fit.
EOD;

        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$this->openai_api_key}",
            ])->timeout(60)->post("{$this->openai_api_base}/chat/completions", [
                'model'       => 'gpt-4o',
                'temperature' => 0.85,
                'top_p'       => 0.9,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a professional LinkedIn content creator.'],
                    ['role' => 'user',   'content' => trim($prompt)],
                ],
            ]);

            if (!$response->successful()) {
                if ($response->status() === 429) {
                    $this->notifyRateLimit('OpenAI Chat Completions', [
                        'endpoint' => '/chat/completions',
                        'context'  => 'generatePost.main',
                        'user_id'  => $user->id ?? null,
                        'body'     => $response->body(),
                    ]);
                    return ['post' => null, 'error' => 'Our system is a bit busy right now—please try again soon.', 'credits' => $user->credits];
                }
                throw new \Exception($response->body());
            }

            $postContent = $response->json()['choices'][0]['message']['content'] ?? '';
        } catch (\Exception $e) {
            return ['post' => null, 'error' => 'Our system is a bit busy right now—please try again soon.', 'credits' => $user->credits];
        }

        $postContent = $this->cleanPostContent($postContent);
        $postContent = $this->optimizeForLinkedIn($postContent, $charLimitFinal, $minChar, $language);

        $plain  = trim(strip_tags($postContent));
        $length = mb_strlen($plain);
        if (($length < $minChar || $length > $charLimitFinal) && abs($length - $charLimitFinal) > ($charLimitFinal * 0.10)) {
            $retryPrompt = $prompt . "\n\nAdjust the post to fit between {$minChar} and {$charLimitFinal} characters as closely as possible, preserving structure, headings, bullets, final question, and 7 hashtags. Do not truncate.";
            try {
                $retryResponse = Http::withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => "Bearer {$this->openai_api_key}",
                ])->timeout(40)->post("{$this->openai_api_base}/chat/completions", [
                    'model'       => 'gpt-4o',
                    'temperature' => 0.7,
                    'top_p'       => 0.9,
                    'messages'    => [
                        ['role' => 'system', 'content' => 'You are a professional LinkedIn content creator.'],
                        ['role' => 'user',   'content' => trim($retryPrompt)],
                    ],
                ]);

                if ($retryResponse->status() === 429) {
                    $this->notifyRateLimit('OpenAI Chat Completions', [
                        'endpoint' => '/chat/completions',
                        'context'  => 'generatePost.retry',
                        'user_id'  => $user->id ?? null,
                        'body'     => $retryResponse->body(),
                    ]);
                    return ['post' => null, 'error' => 'Hurray! We have so much workload — try next time.', 'credits' => $user->credits];
                }

                if ($retryResponse->successful()) {
                    $postContent = $retryResponse->json()['choices'][0]['message']['content'] ?? $postContent;
                    $postContent = $this->cleanPostContent($postContent);
                    $postContent = $this->optimizeForLinkedIn($postContent, $charLimitFinal, $minChar, $language);
                }
            } catch (\Exception $e) {
            }
        }

        $user->decrement('credits');
        return [
            'post'          => $postContent,
            'inspired_by'   => $personaName,
            'custom_prompt' => trim($prompt),
            'optimized'     => true,
            'credits'       => $user->credits,
            'error'         => null,
        ];
    }

    protected function determineDynamicCharLimit($news_text, $language)
    {
        $textLength = mb_strlen($news_text);
        $baseLimit = 1500;
        if ($textLength > 500) $baseLimit = 2500;
        elseif ($textLength > 200) $baseLimit = 2000;
        $verboseLanguages = ['de', 'fr', 'es', 'it', 'pt'];
        if (in_array($language, $verboseLanguages)) $baseLimit += 200;
        return min($baseLimit, 3000);
    }

    public function removeEmojis($text)
    {
        $regexPattern = '/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{200D}\x{2640}-\x{2642}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]+/u';
        return preg_replace($regexPattern, '', $text);
    }

    public function generatePostApi(Request $request)
    {
        $data = $request->json()->all();

        $topic      = $data['topic']      ?? '';
        $influencer = $data['influencer'] ?? '';
        $charLimit  = isset($data['charLimit']) ? intval($data['charLimit']) : null;
        $field      = $data['field']      ?? null;
        $language   = $data['language']   ?? 'en';

        if (empty($topic)) {
            return response()->json(['error' => "Missing 'topic' in request body."], 400);
        }

        $result = $this->generatePost($topic, $influencer, $charLimit, $field, $language);

        return response()->json([
            'post'        => $result['post'] ?? null,
            'inspired_by' => $result['inspired_by'] ?? null,
            'error'       => $result['error'] ?? null,
            'credits'     => $result['credits'] ?? null,
        ]);
    }

    // ======= LinkedIn upload/publish (unchanged, minor cleaning) =======
    protected function getUserLinkedinProfile()
    {
        $user = Auth::user();
        return $user ? LinkedinProfile::where('user_id', $user->id)->first() : null;
    }

    protected function registerAndUploadToLinkedIn($userId, $accessToken, $localFilePath, $mediaType, $recipeUrn)
    {
        try {
            if (strpos($mediaType, 'video/') === 0) $mediaType = 'video/mp4';

            $profile = LinkedinProfile::where('user_id', $userId)->first();
            if (!$profile || !$profile->person_id) {
                Log::error("registerAndUploadToLinkedIn: No LinkedIn profile for user_id={$userId}");
                return null;
            }

            $person_urn = "urn:li:person:" . $profile->person_id;
            $auth_headers = [
                "Authorization" => "Bearer " . $accessToken,
                "Content-Type"  => "application/json",
                "X-Restli-Protocol-Version" => "2.0.0"
            ];

            $registerPayload = [
                "registerUploadRequest" => [
                    "recipes" => [$recipeUrn],
                    "owner"   => $person_urn,
                    "serviceRelationships" => [[
                        "relationshipType" => "OWNER",
                        "identifier" => "urn:li:userGeneratedContent"
                    ]]
                ]
            ];

            $registerResponse = Http::withHeaders($auth_headers)
                ->post("https://api.linkedin.com/v2/assets?action=registerUpload", $registerPayload);

            if (!$registerResponse->successful()) {
                Log::error("registerAndUploadToLinkedIn: Registration failed: " . $registerResponse->body());
                return null;
            }

            $registerData = $registerResponse->json();
            $uploadUrl = $registerData['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'] ?? null;
            $assetUrn  = $registerData['value']['asset'] ?? null;
            if (!$uploadUrl || !$assetUrn) {
                Log::error("registerAndUploadToLinkedIn: Missing upload URL or asset URN");
                return null;
            }

            $fileContent = @file_get_contents($localFilePath);
            if ($fileContent === false) {
                Log::error("registerAndUploadToLinkedIn: Failed reading file: " . $localFilePath);
                return null;
            }

            $uploadResponse = Http::withHeaders([
                "Content-Type" => $mediaType,
                "X-Restli-Protocol-Version" => "2.0.0"
            ])->withBody($fileContent, $mediaType)->put($uploadUrl);

            if (!$uploadResponse->successful()) {
                Log::error("registerAndUploadToLinkedIn: Upload failed: " . $uploadResponse->body());
                return null;
            }

            return $assetUrn;
        } catch (\Exception $ex) {
            Log::error("registerAndUploadToLinkedIn: Exception: " . $ex->getMessage());
            return null;
        }
    }

    protected function mapResolution($aspectRatio, $resolution)
    {
        return match ($aspectRatio) {
            "1:1"  => "1024x1024",
            "9:16" => "1024x1792",
            "16:9", "4:3", "3:2" => "1792x1024",
            default => "1024x1024",
        };
    }

    public function generateImageIdea(Request $request)
    {
        $request->validate([
            'post_content' => 'required|string',
            'aspect_ratio' => 'nullable|string',
            'resolution'   => 'nullable|string',
            'style'        => 'nullable|string'
        ]);
        $postContent = $request->input('post_content');
        $aspectRatio = $request->input('aspect_ratio', '1:1');
        $resolution  = $request->input('resolution', '1024x1024');
        $style       = $request->input('style', 'realistic');

        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized: No user found.'], 401);
        if ($user->credits < 3) return response()->json(['error' => 'You need at least 3 credits to generate an AI image.'], 403);

        $dalleResolution = $this->mapResolution($aspectRatio, $resolution);

        $styleMap = [
            "realistic"    => "realistic, photographic, professional",
            "cartoonify"   => "cartoon, playful, fun illustration",
            "cardboard"    => "paper craft, cardboard texture, crafty look",
            "film-noir"    => "film noir, dramatic black and white, cinematic",
            "watercolor"   => "watercolor, artistic, painted effect",
            "cyberpunk"    => "cyberpunk, neon, futuristic",
            "oil-painting" => "oil painting, classical art style"
        ];
        $styleWording = $styleMap[$style] ?? $style;

        $ratioMap = [
            "1:1"   => "square",
            "4:3"   => "classic 4:3 photo",
            "16:9"  => "wide, 16:9 aspect ratio",
            "3:2"   => "3:2 aspect ratio",
            "9:16"  => "vertical, 9:16 mobile format"
        ];
        $aspectDesc = $ratioMap[$aspectRatio] ?? $aspectRatio;

        $gptPrompt = "You are an expert DALL-E prompt engineer. Given a LinkedIn post, ignore the wording and instead create a concise, vivid, high-detail image prompt that visualizes the core idea. Avoid text/logos/brands. Be specific about setting, people, actions, mood, and key visuals. Fit a professional LinkedIn post image. Format: {$aspectDesc}. Style: {$styleWording}.";

        $promptResponse = Http::withHeaders([
            "Content-Type"  => "application/json",
            "Authorization" => "Bearer {$this->openai_api_key}",
        ])->timeout(40)->post("{$this->openai_api_base}/chat/completions", [
            "model"    => "gpt-4o",
            "messages" => [
                ["role" => "system", "content" => $gptPrompt],
                ["role" => "user", "content" => $postContent]
            ],
            "max_tokens" => 120,
            "temperature" => 0.8,
        ]);

        if (!$promptResponse->successful()) {
            return response()->json(['error' => 'Failed to generate image idea.'], 500);
        }
        $imagePrompt = trim($promptResponse->json()['choices'][0]['message']['content'] ?? '');

        $dalleImageUrl = $this->generateImageWithDalle($imagePrompt, $dalleResolution);
        if (!$dalleImageUrl) return response()->json(['error' => 'Failed to generate DALL-E image.'], 500);

        $user->credits = $user->credits - 3;
        $user->save();

        return response()->json([
            'image_prompt' => $imagePrompt,
            'image_url'    => $dalleImageUrl,
            'credits'      => $user->credits
        ]);
    }

    protected function generateImageWithDalle($prompt, $resolution = "1024x1024")
    {
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer {$this->openai_api_key}",
            ])->timeout(60)->post("{$this->openai_api_base}/images/generations", [
                "model" => "dall-e-3",
                "prompt" => $prompt,
                "n" => 1,
                "size" => $resolution,
                "response_format" => "url"
            ]);

            if (!$response->successful()) {
                Log::error("DALL-E API error: " . $response->body());
                return null;
            }
            $data = $response->json();
            return $data['data'][0]['url'] ?? null;
        } catch (\Exception $e) {
            Log::error("DALL-E exception: " . $e->getMessage());
            return null;
        }
    }

    public function linkedinPostApi(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            Log::error("linkedinPostApi: Not logged in (Auth::user() is null).");
            return response()->json(["error" => "Not logged in"], 401);
        }

        $profile = LinkedinProfile::where('user_id', $user->id)->first();
        if (!$profile || !$profile->access_token) {
            Log::error("linkedinPostApi: No LinkedIn access token found in DB for user_id={$user->id}.");
            return response()->json(["error" => "No LinkedIn access token found in DB for this user"], 401);
        }

        $accessToken = $profile->access_token;
        $personId = $profile->person_id;

        $data = $request->all();
        $schedule_option = $data['schedule'] ?? "draft";
        $scheduled_iso = $data['scheduled_datetime'] ?? null;
        $timezone = $data['timezone'] ?? "UTC";
        $post_text = $data['post'] ?? "";

        $mediaUrl = null;
        $mediaType = null;
        $localFilePath = null;
        $mediaAsset = null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaType = $request->input('media_type') ?: $file->getMimeType();
            $fileName = 'media_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = storage_path('app/public/uploads/' . $fileName);

            if ($file->move(storage_path('app/public/uploads'), $fileName)) {
                $mediaUrl = asset('storage/uploads/' . $fileName);
                $localFilePath = $filePath;
                Log::info("linkedinPostApi: Uploaded file for user_id={$user->id}, path: {$localFilePath}");

                if ($schedule_option === "now") {
                    if (strpos($mediaType, 'image/') === 0) {
                        $mediaAsset = $this->registerAndUploadToLinkedIn($user->id, $accessToken, $localFilePath, $mediaType, "urn:li:digitalmediaRecipe:feedshare-image");
                    } elseif (strpos($mediaType, 'video/') === 0) {
                        $mediaAsset = $this->registerAndUploadToLinkedIn($user->id, $accessToken, $localFilePath, $mediaType, "urn:li:digitalmediaRecipe:feedshare-video");
                    } elseif (strpos($mediaType, 'application/') === 0) {
                        $mediaAsset = $this->registerAndUploadToLinkedIn($user->id, $accessToken, $localFilePath, $mediaType, "urn:li:digitalmediaRecipe:feedshare-document");
                    }
                }
            }
        } elseif ($request->has('media_url')) {
            $mediaUrl = $request->input('media_url');
            $mediaType = $request->input('media_type') ?: 'image/jpeg';

            if ($schedule_option === "now" && $mediaUrl) {
                $tmpFileName = 'media_' . Str::random(10) . '.jpg';
                $tmpPath = storage_path('app/public/uploads/' . $tmpFileName);
                try {
                    $imageContent = file_get_contents($mediaUrl);
                    if ($imageContent !== false) {
                        file_put_contents($tmpPath, $imageContent);
                        $localFilePath = $tmpPath;
                        $mediaAsset = $this->registerAndUploadToLinkedIn($user->id, $accessToken, $localFilePath, $mediaType, "urn:li:digitalmediaRecipe:feedshare-image");
                    }
                } catch (\Exception $e) {
                    Log::error("linkedinPostApi: Failed to download AI image: " . $e->getMessage());
                    $mediaAsset = null;
                }
            }
        }

        $post_text = $this->transformHTMLToFancyUnicode($post_text);

        if ($schedule_option === "draft") {
            Log::info("linkedinPostApi: user_id={$user->id} created a draft.");
            return response()->json([
                "message" => "Draft generated successfully!",
                "post" => $post_text,
                "media_url" => $mediaUrl
            ]);
        }

        if ($schedule_option === "now") {
            Log::info("linkedinPostApi: publishing immediately for user_id={$user->id}.");
            $postUrn = $this->postToLinkedIn($user->id, $accessToken, $post_text, $mediaAsset, $mediaType);

            if ($postUrn) {
                ScheduledPost::create([
                    'user_id'            => $personId,
                    'access_token'       => $accessToken,
                    'post_text'          => $post_text,
                    'media_url'          => $mediaUrl,
                    'media_type'         => $mediaType,
                    'media_asset'        => $mediaAsset,
                    'post_urn'           => $postUrn,
                    'scheduled_datetime' => now()->format('Y-m-d H:i'),
                    'timezone'           => $timezone,
                    'status'             => 'published',
                ]);

                return response()->json([
                    "message" => "Post published successfully!",
                    "post_urn" => $postUrn,
                ]);
            } else {
                return response()->json(["error" => "Failed to publish post"], 500);
            }
        }
        if ($schedule_option === "scheduled") {
            try {
                $dtUser = \Carbon\Carbon::parse($scheduled_iso); // parse as UTC
                $mysqlDatetime = $dtUser->copy()->setTimezone('UTC')->format('Y-m-d H:i');
            } catch (\Exception $e) {
                return response()->json(["error" => "Invalid scheduled_datetime format"], 400);
            }

            $scheduledPost = ScheduledPost::create([
                'user_id'            => $personId,
                'access_token'       => $accessToken,
                'post_text'          => $post_text,
                'media_url'          => $mediaUrl,
                'media_type'         => $mediaType,
                'media_asset'        => $mediaAsset,
                'post_urn'           => null,
                'scheduled_datetime' => $mysqlDatetime,
                'timezone'           => $timezone,
                'status'             => 'pending',
            ]);

            // Display time in user's timezone (not UTC)
            $dtShow = $dtUser->setTimezone($timezone)->format('g:i A');
            return response()->json([
                "message" => "Post scheduled for $dtShow ($timezone)!",
                "scheduled_post_id" => $scheduledPost->id,
                "scheduled_datetime" => $mysqlDatetime,
            ]);
        }


        Log::warning("linkedinPostApi: user_id={$user->id} invalid schedule option: {$schedule_option}");
        return response()->json(["error" => "Invalid schedule option"], 400);
    }

    protected function postToLinkedIn($userId, $accessToken, $message, $mediaAsset = null, $mediaType = null)
    {
        Log::info("postToLinkedIn: Start processing for user_id={$userId}.");

        $profile = LinkedinProfile::where('user_id', $userId)->first();
        if (!$profile || !$profile->person_id) {
            Log::error("postToLinkedIn: No LinkedIn person_id for user_id={$userId}.");
            return null;
        }
        $personUrn = "urn:li:person:" . $profile->person_id;

        $plainText = preg_replace(
            ['/\<img[^\>]*\>/i', '/\<video.*?\<\/video\>/is', '/\<embed[^\>]*\>/i', '/\<br\>/i'],
            ['', '', '', "\n"],
            strip_tags($message)
        );
        $plainText = trim($plainText) ?: ' ';
        if (mb_strlen($plainText) > 8000) $plainText = mb_substr($plainText, 0, 8000);

        $payload = [
            "author"          => $personUrn,
            "lifecycleState"  => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary"    => ["text" => $plainText],
                    "shareMediaCategory" => $mediaAsset ? strtoupper(explode('/', $mediaType)[0]) : "NONE",
                ]
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC"
            ]
        ];

        if ($mediaAsset) {
            $payload["specificContent"]["com.linkedin.ugc.ShareContent"]["media"] = [[
                "status"      => "READY",
                "description" => ["text" => ""],
                "media"       => $mediaAsset,
                "title"       => ["text" => ""]
            ]];
        }

        try {
            $response = Http::withHeaders([
                "Authorization"             => "Bearer {$accessToken}",
                "Content-Type"              => "application/json",
                "X-Restli-Protocol-Version" => "2.0.0",
            ])->withBody(json_encode($payload), 'application/json')
                ->post("https://api.linkedin.com/v2/ugcPosts");

            if ($response->status() === 201) return $response->header('x-restli-id');

            if ($response->status() === 422) {
                $json = $response->json();
                Log::warning("postToLinkedIn: Duplicate detected: " . ($json['message'] ?? 'unknown'));
                return null;
            }

            Log::error("postToLinkedIn: Failed to post ({$response->status()}): " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("postToLinkedIn: Exception: " . $e->getMessage());
            return null;
        }
    }

    public function getUserScheduledPosts(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(["error" => "Not logged in"], 401);

        $profile = LinkedinProfile::where('user_id', $user->id)->first();
        if (!$profile || !$profile->person_id) return response()->json(["error" => "No LinkedIn profile found for this user"], 404);

        $personId = $profile->person_id;
        $scheduledPosts = ScheduledPost::where('user_id', $personId)->get();
        return response()->json(["scheduled_posts" => $scheduledPosts]);
    }

    public function getScheduledPost(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(["error" => "Not logged in"], 401);

        $profile = LinkedinProfile::where('user_id', $user->id)->first();
        if (!$profile || !$profile->person_id) return response()->json(["error" => "No LinkedIn profile found for this user"], 404);

        $personId = $profile->person_id;
        $post = ScheduledPost::where('id', $id)->where('user_id', $personId)->first();
        if (!$post) return response()->json(["error" => "Scheduled post not found"], 404);

        return response()->json(["scheduled_post" => $post]);
    }

    public function updateScheduledPost(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(["error" => "Not logged in"], 401);

        $profile = LinkedinProfile::where('user_id', $user->id)->first();
        if (!$profile || !$profile->person_id) return response()->json(["error" => "No LinkedIn profile found for this user"], 404);

        $personId = $profile->person_id;
        $scheduledPostId = $request->input('id');
        if (!$scheduledPostId) return response()->json(["error" => "Missing scheduled post id"], 400);

        $scheduledPost = ScheduledPost::where('id', $scheduledPostId)->where('user_id', $personId)->first();
        if (!$scheduledPost) return response()->json(["error" => "Scheduled post not found"], 404);

        $request->validate([
            'post_text'          => 'required|string',
            'scheduled_datetime' => 'required|date',
        ]);

        $cleanedPost = $request->input('post_text');
        $cleanedPost = preg_replace('/(?:<br>\s*)*#([^\s<]+).*?(?=<br>|$)/i', '', $cleanedPost);
        $cleanedPost = preg_replace('/<p>---<\/p>.*$/is', '', $cleanedPost);
        $cleanedPost = preg_replace('/(<br>\s*)+$/', '', $cleanedPost);
        $scheduledPost->post_text = trim($cleanedPost);

        try {
            $carbon = Carbon::parse($request->input('scheduled_datetime'));
            $scheduledPost->scheduled_datetime = $carbon->format('Y-m-d H:i');
        } catch (\Exception $e) {
            return response()->json(["error" => "Invalid scheduled_datetime format"], 400);
        }

        if ($request->has('remove_media') && $request->input('remove_media') === 'true') {
            $scheduledPost->media_url = null;
            $scheduledPost->media_type = null;
            $scheduledPost->media_url = null;
        }

        $scheduledPost->save();
        return response()->json(["message" => "Scheduled post updated successfully.", "scheduled_post" => $scheduledPost]);
    }

    public function deleteScheduledPost(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(["error" => "Not logged in"], 401);

        $profile = LinkedinProfile::where('user_id', $user->id)->first();
        if (!$profile || !$profile->person_id) return response()->json(["error" => "No LinkedIn profile found for this user"], 404);

        $personId = $profile->person_id;
        $scheduledPostId = $request->input('id');
        if (!$scheduledPostId) return response()->json(["error" => "Missing scheduled post id"], 400);

        $scheduledPost = ScheduledPost::where('id', $scheduledPostId)->where('user_id', $personId)->first();
        if (!$scheduledPost) return response()->json(["error" => "Scheduled post not found"], 404);

        $scheduledPost->delete();
        return response()->json(["message" => "Scheduled post deleted successfully."]);
    }

    public function getAllPostMetrics(Request $request)
    {
        $accessToken = 'AQXe0W1hgQNcuoHT1fa1PDUXE9X5E-Z6WRt-PiSHUfF3XZgnTdf7OYWedS7YscBQTKY-0BYwUv8emkYhpnRLrp6yW5jYPHLEZEzC7oMiq4ZuFHdt6-kcY8VUnkROPWe1qI1c2A_pU-9Dtv_Z3T3TpQ9mEk2nRMp4zcpFRUfg0-caXy5A4MvSU9xmyvAfmwjq-S4zKkC7-DlnxtirYPdLLT8oI1zPb8N0AGu_chLQgCK8LibA9LMkBi-2c2Wekk-_ILFzUB0q6V9gGRR1nj-BHebPJ6-TO48qhgJpdMQdfMlXcXz19xwItAqhHlrMdudRkZOxFrWZ0pa7_YP6_WnkMwQyAIUwUg';
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Not logged in'], 401);

        $posts = ScheduledPost::where('user_id', $user->id)->whereNotNull('post_urn')->get();
        $metrics = [];

        foreach ($posts as $post) {
            $urn        = $post->post_urn;
            $encodedUrn = rawurlencode($urn);

            $sa = Http::withHeaders([
                'Authorization'             => "Bearer {$accessToken}",
                'X-Restli-Protocol-Version' => '2.0.0',
            ])->get("https://api.linkedin.com/v2/socialActions/{$encodedUrn}");
            $saData = $sa->ok() ? $sa->json() : [];

            $org = Http::withHeaders([
                'Authorization'             => "Bearer {$accessToken}",
                'X-Restli-Protocol-Version' => '2.0.0',
            ])->get("https://api.linkedin.com/v2/organizationalEntityShareStatistics?q=shares&shares[0]={$encodedUrn}");
            $orgElem = $org->ok() ? ($org->json()['elements'][0] ?? []) : [];

            $metrics[$urn] = [
                'post_text'            => $post->post_text,
                'comments'             => $saData['commentCount']    ?? 0,
                'likes'                => $saData['likeCount']       ?? 0,
                'reposts'              => $saData['shareCount']      ?? 0,
                'impressions'          => $saData['impressionCount'] ?? 0,
                'org_share_count'      => $orgElem['shareCount']      ?? 0,
                'org_like_count'       => $orgElem['likeCount']       ?? 0,
                'org_comment_count'    => $orgElem['commentCount']    ?? 0,
                'org_impression_count' => $orgElem['impressionCount'] ?? 0,
            ];
        }
        return response()->json(['metrics' => $metrics]);
    }

    protected function notifyRateLimit(string $service, array $meta = []): void
    {
        try {
            $recipient = env('ALERT_EMAIL', 'qaziabdurrahman12@gmail.com');
            $cacheKey  = 'rate_limit_alert:' . md5($service . '|' . json_encode($meta));
            if (Cache::get($cacheKey)) return;
            Cache::put($cacheKey, 1, now()->addMinutes(15));

            $subject = "[Rate Limit] {$service} hit 429";
            $lines = [
                "A rate limit (HTTP 429) occurred.",
                "Service: {$service}",
                "Time: " . now()->toDateTimeString(),
            ];
            if (!empty($meta)) $lines[] = "Details: " . json_encode($meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $body = implode("\n", $lines);

            Mail::raw($body, function ($m) use ($recipient, $subject) {
                $m->to($recipient)->subject($subject);
            });
        } catch (\Throwable $e) {
            Log::error("notifyRateLimit failed: " . $e->getMessage());
        }
    }

    protected function notifyNewsApiTopHeadlinesLimit(array $meta = []): void
    {
        try {
            Log::warning('notifyNewsApiTopHeadlinesLimit fired (empty news fetch)', $meta);
            $recipient = 'qaziabdurrahman12@gmail.com';
            $service   = 'News Fetcher (RSS/GN)';

            $cacheKey = 'rate_limit_alert:' . md5($service . '|' . json_encode($meta) . '|' . now()->format('YmdHi'));
            if (Cache::get($cacheKey)) {
                Log::info('notifyNewsApiTopHeadlinesLimit skipped due to cooldown.');
                return;
            }
            Cache::put($cacheKey, 1, now()->addMinutes(1));

            $subject = "[Notice] {$service} returned no articles";
            $lines = [
                "An empty result occurred.",
                "Service: {$service}",
                "Time: " . now()->toDateTimeString(),
            ];
            if (!empty($meta)) $lines[] = "Details: " . json_encode($meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $body = implode("\n", $lines);

            Mail::mailer(config('mail.default', 'smtp'))->raw($body, function ($m) use ($recipient, $subject) {
                $m->to($recipient)->subject($subject);
            });

            Log::info("notifyNewsApiTopHeadlinesLimit: email dispatched to {$recipient}");
        } catch (\Throwable $e) {
            Log::error("notifyNewsApiTopHeadlinesLimit failed: " . $e->getMessage());
        }
    }

    public function health()
    {
        return response()->json(["status" => "ok"]);
    }
}
