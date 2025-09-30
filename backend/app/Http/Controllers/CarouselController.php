<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarouselController extends Controller
{
    protected $gnewsApiKey = "2fc54d7733052adb9c2378fdaae8de24"; // put your GNews key here
    protected $openrouterApiKey = "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"; // your openrouter (Deepseek) key
    protected $openrouterApiBase = "https://openrouter.ai/api/v1";

    /**
     * POST /api/generate-options
     * Expects: { "category": "business", "country": "us" }
     * Returns: { "trending_topics": [ { "title": "...", "source": "...", "publishedAt": "...", "description": "..."} ] }
     */
    public function fetchTrendingTopics(Request $request)
    {
        $category = $request->input('category', 'general');
        $country  = $request->input('country', 'us');

        $url = "https://gnews.io/api/v4/top-headlines?country={$country}&category={$category}&apikey={$this->gnewsApiKey}";

        try {
            $response = Http::get($url);
            if (!$response->successful()) {
                Log::error("GNews fetch error: " . $response->body());
                return response()->json([
                    "error" => "Failed to fetch trending topics from GNews."
                ], 500);
            }
            $data = $response->json();
            $articles = [];
            if (isset($data['articles'])) {
                foreach ($data['articles'] as $article) {
                    $articles[] = [
                        "title"       => $article['title'] ?? '',
                        "source"      => $article['source']['name'] ?? '',
                        "publishedAt" => $article['publishedAt'] ?? '',
                        "description" => $article['description'] ?? '',
                    ];
                }
            }
            return response()->json([
                "trending_topics" => $articles
            ]);
        } catch (\Exception $e) {
            Log::error("Exception in fetchTrendingTopics: " . $e->getMessage());
            return response()->json([
                "error" => "Exception fetching trending topics."
            ], 500);
        }
    }

    /**
     * POST /api/generate-ppt
     * Expects: { "title": "The news title", "description": "...", "source": "...", "publishedAt": "..." }
     * Returns: { "slides": [ { "title": "...", "content": "..." }, ... ] } (3 slides total)
     *
     * Calls Deepseek (deepseek/deepseek-r1-zero) to produce a 3-slide PPT style summary.
     */
    public function generatePPTSlides(Request $request)
    {
        $title       = $request->input('title', '');
        $description = $request->input('description', '');
        $source      = $request->input('source', '');
        $publishedAt = $request->input('publishedAt', '');
    
        if (empty($title)) {
            return response()->json(["error" => "Missing 'title'"], 400);
        }
    
        // Build a short context
        $context = "Title: {$title}\nSource: {$source}\nDate: {$publishedAt}\nDescription: {$description}";
    
        // UPDATED PROMPT: Ask for 5-7 slides
        $prompt = "
            Create a concise deck with 5 to 7 short slides summarizing the news context below.
            Each slide should have a short heading and 1-2 lines of content.
            Format as JSON with array 'slides', each slide having 'title' and 'content' keys.
            Context:
            {$context}
        ";
    
        try {
            $response = Http::withHeaders([
                "Content-Type"  => "application/json",
                "Authorization" => "Bearer {$this->openrouterApiKey}",
            ])
            ->timeout(60)
            ->retry(3, 2000)
            ->post("{$this->openrouterApiBase}/chat/completions", [
                "model"       => "qwen/qwen2.5-vl-32b-instruct:free",
                "temperature" => 0.7,
                "messages"    => [
                    ["role" => "user", "content" => $prompt]
                ]
            ]);
    
            if (!$response->successful()) {
                Log::error("generatePPTSlides error: " . $response->body());
                return response()->json(["error" => "Deepseek AI error"], 500);
            }
    
            $data = $response->json();
            $rawText = $data['choices'][0]['message']['content'] ?? '';
    
            // Remove possible \boxed{ ... } wrapper
            $rawText = preg_replace('/^\\\\boxed\s*\{\s*/', '', $rawText); 
            $rawText = preg_replace('/\}\s*$/', '', $rawText);
    
            // Attempt to parse the JSON
            $slides = [];
            if (preg_match('/\{.*\}/s', $rawText, $matches)) {
                $jsonStr = $matches[0];
                $decoded = json_decode($jsonStr, true);
                if (isset($decoded['slides']) && is_array($decoded['slides'])) {
                    $slides = $decoded['slides'];
                }
            }
    
            // Fallback if no valid slides found
            if (empty($slides)) {
                $slides = [
                    ["title" => "Slide 1", "content" => $rawText]
                ];
            }
    
            return response()->json(["slides" => $slides]);
        } catch (\Exception $e) {
            Log::error("generatePPTSlides exception: " . $e->getMessage());
            return response()->json(["error" => "Exception generating slides"], 500);
        }
    }
}
