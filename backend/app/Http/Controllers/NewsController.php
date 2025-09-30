<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Http\Client\ConnectionException; // <— for retry-on-DNS/connect

class NewsController extends Controller
{
    // ===================== OpenAI config =====================
    protected $openai_api_key;
    protected $openai_api_base;
    protected $google_trends_api_key;
    protected $google_trends_api_url;

    public function __construct()
    {
        $this->openai_api_key = env('OPENAI_API_KEY');
        $this->openai_api_base = env('OPENAI_API_BASE', 'https://api.openai.com/v1');
        $this->google_trends_api_key = env('GOOGLE_TRENDS_API_KEY');
        $this->google_trends_api_url = env('GOOGLE_TRENDS_API_URL', 'https://api.scrapingdog.com/google_trends');
    }

    // ===================== News fetcher config =====================
    protected string $newsUserAgent = 'Mozilla/5.0 (compatible; LaravelNewsFetcher/1.1)';
    protected int $newsTimeout = 5;             // Reduced from 8 for faster response
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

    // ===================== Low-level HTTP helpers =====================
    protected function n_clean(string $html = null): string
    {
        if (!$html) return '';
        $txt = preg_replace('~\s+~u', ' ', strip_tags($html));
        return trim($txt);
    }
    protected function n_domain(string $url = null): string
    {
        if (!$url) return 'unknown';
        $host = parse_url($url, PHP_URL_HOST) ?: 'unknown';
        return strtolower(preg_replace('~^www\.~i', '', $host));
    }
    protected function n_isGoogleUrl(string $url = null): bool
    {
        if (!$url) return false;
        return (bool)preg_match('~(^|//)(news|www)\.google\.~i', $url) || str_contains($url, 'gstatic.com');
    }
    protected function n_unwrapGoogleUrl(string $url = null): ?string
    {
        if (!$url) return null;
        $qs = parse_url($url, PHP_URL_QUERY);
        if (!$qs) return null;
        parse_str($qs, $q);
        if (!empty($q['url']) && preg_match('~^https?://~i', $q['url'])) {
            return $q['url'];
        }
        return null;
    }

    protected function n_httpGet(string $url, array $headers = [])
    {
        return Http::withHeaders(array_merge([
            'User-Agent'      => $this->newsUserAgent,
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Referer'         => 'https://news.google.com/',
        ], $headers))
            ->withOptions([
                'allow_redirects' => true,
                'connect_timeout' => 3,               // was 4
                'timeout'         => $this->newsTimeout,
                'force_ip_resolve' => 'v4', // More reliable on Windows/VPN
            ])
            ->retry(2, 250, function ($e) {
                return $e instanceof ConnectionException
                    || (is_string($e->getMessage()) && str_contains(strtolower($e->getMessage()), 'could not resolve host'));
            })
            ->get($url);
    }

    protected function n_responseOk($res): bool
    {
        return $res instanceof HttpResponse && $res->ok();
    }
    protected function n_effectiveUrl($res, string $fallback = ''): string
    {
        if ($res instanceof HttpResponse) {
            $stats = $res->handlerStats();
            if (!empty($stats['url'])) return $stats['url'];
        }
        return $fallback;
    }
    protected function n_ts($dt): int
    {
        if ($dt instanceof \DateTimeInterface) return $dt->getTimestamp();
        if (is_int($dt)) return $dt;
        if (is_string($dt) && $dt !== '') {
            $t = strtotime($dt);
            if ($t !== false) return $t;
        }
        return now()->getTimestamp();
    }
    protected function n_iso($dt): string
    {
        if ($dt instanceof \DateTimeInterface) return $dt->format(DATE_ATOM);
        if (is_string($dt) && $dt !== '') {
            $t = strtotime($dt);
            if ($t !== false) return date(DATE_ATOM, $t);
        }
        if (is_int($dt)) return date(DATE_ATOM, $dt);
        return now()->toAtomString();
    }

    // ===================== Feed body caching + flaky-host skipping =====================
    protected function n_badFeedKey(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST) ?: $url;
        return 'badfeed:' . md5(strtolower($host));
    }
    protected function n_shouldSkipFeed(string $url): bool
    {
        return Cache::has($this->n_badFeedKey($url));
    }
    protected function n_markFeedBad(string $url, int $minutes = 30): void
    {
        Cache::put($this->n_badFeedKey($url), 1, now()->addMinutes($minutes));
    }

    protected function n_fetchFeedsPool(array $urls, string $accept = 'application/rss+xml, application/atom+xml, application/xml;q=0.9, */*;q=0.8'): array
    {
        $urls = array_values(array_filter($urls, fn($u) => !$this->n_shouldSkipFeed($u)));

        $bodies = [];
        $misses = [];
        foreach ($urls as $u) {
            $cached = Cache::get('nf:' . md5($accept . '|' . $u));
            if ($cached !== null) {
                $bodies[$u] = $cached;
            } else {
                $misses[] = $u;
            }
        }

        if ($misses) {
            $responses = Http::pool(function ($pool) use ($misses, $accept) {
                foreach ($misses as $u) {
                    $pool->as($u)
                        ->withHeaders([
                            'User-Agent' => $this->newsUserAgent,
                            'Accept'     => $accept,
                        ])
                        ->withOptions([
                            'allow_redirects' => true,
                            'connect_timeout' => 3,               // was 4
                            'timeout'         => $this->newsTimeout,
                            'force_ip_resolve' => 'v4',
                        ])
                        ->retry(2, 250, function ($e) {
                            return $e instanceof ConnectionException
                                || (is_string($e->getMessage()) && str_contains(strtolower($e->getMessage()), 'could not resolve host'));
                        })
                        ->get($u);
                }
            });

            foreach ($misses as $u) {
                $res = $responses[$u] ?? null;
                if ($this->n_responseOk($res)) {
                    $bodies[$u] = $res->body();
                    Cache::put('nf:' . md5($accept . '|' . $u), $bodies[$u], $this->feedCacheTtl);
                } else {
                    $this->n_markFeedBad($u, $this->badFeedCooldownMin);
                    Log::warning("Feed unreachable, temporarily skipped: {$u}");
                }
            }
        }

        return $bodies; // map url => body
    }

    // ===================== Metadata scrapers =====================
    protected function n_metaDescription(string $url): string
    {
        try {
            $r = $this->n_httpGet($url);
            if (!$this->n_responseOk($r)) return '';
            return $this->n_metaDescriptionFromBody($r->body(), $url);
        } catch (\Throwable $e) {
            return '';
        }
    }

    protected function n_metaDescriptionFromBody(string $html = null, string $url = ''): string
    {
        if (!$html) return '';
        try {
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);
            foreach (
                [
                    "//meta[@property='og:description']/@content",
                    "//meta[@name='twitter:description']/@content",
                    "//meta[@name='description']/@content",
                ] as $q
            ) {
                $n = $xpath->query($q);
                if ($n && $n->length) return $this->n_clean($n->item(0)->nodeValue ?? '');
            }
            $p = $xpath->query('//p');
            if ($p && $p->length) return $this->n_clean($dom->saveHTML($p->item(0)));
        } catch (\Throwable $e) {
        }
        return '';
    }

    protected function n_fullText(string $url, int $min = 200): string
    {
        try {
            $r = $this->n_httpGet($url);
            if (!$this->n_responseOk($r)) return '';
            return $this->n_fullTextFromBody($r->body(), $url, $min);
        } catch (\Throwable $e) {
            return '';
        }
    }

    protected function n_fullTextFromBody(string $html = null, string $url = '', int $min = 200): string
    {
        if (!$html) return '';
        try {
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);
            foreach (['//script', '//style', '//noscript', '//header', '//footer', '//aside', '//form', '//nav'] as $q) {
                foreach ($xpath->query($q) as $node) $node->parentNode?->removeChild($node);
            }
            $candidates = $xpath->query('//article|//main|//section|//div[contains(@id,"content") or contains(@id,"main") or contains(@class,"content") or contains(@class,"article") or contains(@class,"post")]');
            $best = null;
            $bestLen = 0;
            if ($candidates && $candidates->length) {
                foreach ($candidates as $cand) {
                    $text = $this->n_clean($dom->saveHTML($cand));
                    $len  = mb_strlen($text);
                    if ($len > $bestLen) {
                        $bestLen = $len;
                        $best = $cand;
                    }
                }
            } else {
                $best = $dom->getElementsByTagName('body')->item(0);
            }
            if (!$best) return '';
            $paras = [];
            foreach ($xpath->query('.//p|.//li', $best) as $node) {
                $t = $this->n_clean($dom->saveHTML($node));
                if ($t && mb_strlen($t) > 40) $paras[] = $t;
            }
            $joined = trim(implode("\n\n", $paras));
            if (mb_strlen($joined) >= $min) return $joined;
        } catch (\Throwable $e) {
        }
        return $this->n_metaDescriptionFromBody($html, $url);
    }

    // ===================== Google News helpers (country & category) =====================
    /** Map ISO country -> [hl, gl, ceid] suitable for Google News. */
    protected function n_googleLocale(string $country): array
    {
        $c = strtoupper($country ?: 'US');

        // Default builder: prefer English editions where possible
        $default = fn($cc) => ['hl' => 'en', 'gl' => $cc, 'ceid' => $cc . ':en'];

        $map = [
            // Global (fallback to US English top stories)
            'GLOBAL' => ['hl' => 'en', 'gl' => 'US', 'ceid' => 'US:en'],

            // English-first where possible
            'US' => ['hl' => 'en-US', 'gl' => 'US', 'ceid' => 'US:en'],
            'GB' => ['hl' => 'en-GB', 'gl' => 'GB', 'ceid' => 'GB:en-GB'],
            'AU' => ['hl' => 'en-AU', 'gl' => 'AU', 'ceid' => 'AU:en'],
            'CA' => ['hl' => 'en-CA', 'gl' => 'CA', 'ceid' => 'CA:en-CA'],
            'IE' => ['hl' => 'en-IE', 'gl' => 'IE', 'ceid' => 'IE:en-IE'],
            'IN' => ['hl' => 'en-IN', 'gl' => 'IN', 'ceid' => 'IN:en'],
            'PK' => ['hl' => 'en-PK', 'gl' => 'PK', 'ceid' => 'PK:en-PK'],
            'SG' => ['hl' => 'en-SG', 'gl' => 'SG', 'ceid' => 'SG:en'],
            'PH' => ['hl' => 'en-PH', 'gl' => 'PH', 'ceid' => 'PH:en-PH'],
            'NZ' => ['hl' => 'en-NZ', 'gl' => 'NZ', 'ceid' => 'NZ:en-NZ'],
            'MY' => ['hl' => 'en-MY', 'gl' => 'MY', 'ceid' => 'MY:en'],
            'NG' => ['hl' => 'en-NG', 'gl' => 'NG', 'ceid' => 'NG:en-NG'],
            'KE' => ['hl' => 'en-KE', 'gl' => 'KE', 'ceid' => 'KE:en-KE'],
            'ZA' => ['hl' => 'en-ZA', 'gl' => 'ZA', 'ceid' => 'ZA:en-ZA'],
            'AE' => ['hl' => 'en-AE', 'gl' => 'AE', 'ceid' => 'AE:en'],
            'SA' => ['hl' => 'en-SA', 'gl' => 'SA', 'ceid' => 'SA:en'],
            'HK' => ['hl' => 'en', 'gl' => 'HK', 'ceid' => 'HK:en'], // use English edition
            'TW' => ['hl' => 'en', 'gl' => 'TW', 'ceid' => 'TW:en'],
            'CH' => ['hl' => 'en', 'gl' => 'CH', 'ceid' => 'CH:en'],
            'NO' => ['hl' => 'en', 'gl' => 'NO', 'ceid' => 'NO:en'],
            'SE' => ['hl' => 'en', 'gl' => 'SE', 'ceid' => 'SE:en'],
            'NL' => ['hl' => 'en', 'gl' => 'NL', 'ceid' => 'NL:en'],
            'ES' => ['hl' => 'en', 'gl' => 'ES', 'ceid' => 'ES:en'],
            'PT' => ['hl' => 'en', 'gl' => 'PT', 'ceid' => 'PT:en'],
            'IT' => ['hl' => 'en', 'gl' => 'IT', 'ceid' => 'IT:en'],
            'FR' => ['hl' => 'fr-FR', 'gl' => 'FR', 'ceid' => 'FR:fr'], // native for France
            'DE' => ['hl' => 'de-DE', 'gl' => 'DE', 'ceid' => 'DE:de'], // native for Germany
            'GR' => ['hl' => 'el-GR', 'gl' => 'GR', 'ceid' => 'GR:el'], // native for Greece
            'RO' => ['hl' => 'ro', 'gl' => 'RO', 'ceid' => 'RO:ro'],
            'RU' => ['hl' => 'ru', 'gl' => 'RU', 'ceid' => 'RU:ru'],
            'UA' => ['hl' => 'uk', 'gl' => 'UA', 'ceid' => 'UA:uk'],
            'JP' => ['hl' => 'ja', 'gl' => 'JP', 'ceid' => 'JP:ja'],
            'BR' => ['hl' => 'pt-BR', 'gl' => 'BR', 'ceid' => 'BR:pt-419'],
            'CN' => ['hl' => 'zh-CN', 'gl' => 'CN', 'ceid' => 'CN:zh-Hans'],
            'EG' => ['hl' => 'ar', 'gl' => 'EG', 'ceid' => 'EG:ar'],
            'IL' => ['hl' => 'en', 'gl' => 'IL', 'ceid' => 'IL:en'],
            'PE' => ['hl' => 'en', 'gl' => 'PE', 'ceid' => 'PE:en'],
        ];

        $supported = array_change_key_case($map, CASE_UPPER);
        return $supported[$c] ?? $default($c);
    }

    /** Category -> Google News topic code */
    protected function n_categoryToTopic(?string $category): ?string
    {
        $c = strtolower($category ?? 'top');
        return match ($c) {
            'top', 'all'     => null, // use Top Stories
            'world'          => 'WORLD',
            'nation', 'local' => 'NATION',
            'business'       => 'BUSINESS',
            'technology', 'tech' => 'TECHNOLOGY',
            'entertainment'  => 'ENTERTAINMENT',
            'science'        => 'SCIENCE',
            'health'         => 'HEALTH',
            'sports'        => 'SPORTS',
            default          => null,
        };
    }

    /** Build Google News RSS feeds for country + category (and optional search query). */
    protected function n_googleNewsFeeds(?string $query, ?string $category, string $country): array
    {
        if (strtolower($country) === 'global') $country = 'GLOBAL';

        $loc = $this->n_googleLocale($country);
        $hl = urlencode($loc['hl']);
        $gl = urlencode($loc['gl']);
        $ceid = urlencode($loc['ceid']);

        $feeds = [];
        $topic = $this->n_categoryToTopic($category);

        if ($query) {
            $q = urlencode($query);
            $feeds[] = "https://news.google.com/rss/search?q={$q}&hl={$hl}&gl={$gl}&ceid={$ceid}";
        } else {
            if ($topic) {
                $feeds[] = "https://news.google.com/rss/headlines/section/topic/{$topic}?hl={$hl}&gl={$gl}&ceid={$ceid}";
            } else {
                $feeds[] = "https://news.google.com/rss?hl={$hl}&gl={$gl}&ceid={$ceid}";
            }
        }
        return $feeds;
    }

    /** Category keywords (kept for compatibility) */
    protected function n_categoryKeywords(?string $category): array
    {
        $c = strtolower($category ?? '');
        return match ($c) {
            'world'         => ['world', 'international', 'global'],
            'nation', 'local' => ['nation', 'national', 'india', 'us', 'uk'],
            'business'      => ['business', 'market', 'economy', 'finance', 'startup'],
            'technology', 'tech' => ['tech', 'technology', 'software', 'ai', 'gadget', 'internet'],
            'science'       => ['science', 'research', 'space', 'nasa', 'study'],
            'health'        => ['health', 'covid', 'vaccine', 'medical', 'wellness'],
            'entertainment' => ['entertainment', 'film', 'movie', 'tv', 'celebrity', 'music'],
            'sports'        => ['sport', 'sports', 'football', 'cricket', 'nba', 'fifa', 'olympics'],
            default         => [],
        };
    }

    protected function n_localFeeds(string $country): array
    {
        return [];
    }

    protected function n_buildFeeds(?string $category, string $country, ?string $query = null): array
    {
        $feeds = [];
        $feeds = array_merge($feeds, $this->n_googleNewsFeeds($query, $category, $country));
        $feeds = array_values(array_unique($feeds));
        return $feeds;
    }

    // ===================== Feed parsing =====================
    protected function n_resolvePublisher(string $rawLink, ?string $summaryHtml, ?string $sourceHref, ?string $sourceName): string
    {
        try {
            // ⚡ FAST PATH: if it's not a Google News URL, use it as-is (no HTTP)
            if ($rawLink && !$this->n_isGoogleUrl($rawLink)) {
                return $rawLink;
            }

            // Try to extract the external link from the Google summary HTML (no HTTP)
            if ($summaryHtml) {
                $doc = new \DOMDocument();
                @$doc->loadHTML($summaryHtml);
                foreach ($doc->getElementsByTagName('a') as $a) {
                    $href = $a->getAttribute('href');
                    if (!$href) continue;
                    if (str_starts_with($href, '/')) $href = 'https://news.google.com' . $href;
                    if (!$this->n_isGoogleUrl($href)) {
                        $rawLink = $href;
                        break;
                    }
                    $cand = $this->n_unwrapGoogleUrl($href);
                    if ($cand && !$this->n_isGoogleUrl($cand)) {
                        $rawLink = $cand;
                        break;
                    }
                }
            }

            // Unwrap google.com/url if present
            if ($cand = $this->n_unwrapGoogleUrl($rawLink)) $rawLink = $cand;

            // If still Google, just return it (avoid extra HTTP hops for speed)
            return $rawLink ?: '';
        } catch (\Throwable $e) {
            return $rawLink ?: '';
        }
    }

    protected function n_parseFeed(string $xml, string $feedUrl, array $topicTerms = [], int $perFeedItems = 2): array
    {
        $out = [];
        if (!$xml) return $out;

        $rss = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$rss) return $out;

        $items = [];
        if (isset($rss->channel->item)) {
            $items = $rss->channel->item;
        } elseif (isset($rss->entry)) {
            $items = $rss->entry;
        }
        $isGoogleFeed = str_contains($feedUrl, 'news.google.com');

        $count = 0;
        foreach ($items as $it) {
            $title = $this->n_clean((string)($it->title ?? ''));
            $link  = (string)($it->link['href'] ?? $it->link ?? '');
            $descH = (string)($it->description ?? $it->summary ?? '');
            $desc  = $this->n_clean($descH);
            $date  = (string)($it->pubDate ?? $it->updated ?? '');
            $srcEl = $it->source ?? null;
            $srcHref = $srcEl ? (string)$srcEl['url'] : null;
            $srcName = $srcEl ? (string)$srcEl : null;

            if (!$title || !$link) continue;

            $final = $isGoogleFeed
                ? $this->n_resolvePublisher($link, $descH, $srcHref, $srcName)
                : $link;

            $src = $this->n_domain($final) ?: $this->n_domain($feedUrl);

            $out[] = [
                'title' => $title,
                'summary' => $desc,
                'url' => $final,
                'source' => $src,
                'date_dt' => $date ? date_create($date) : now(),
                'raw_summary_html' => $descH,
            ];
            $count++;
            if ($count >= $perFeedItems) break;
        }
        return $out;
    }

    // ===================== NEWS: country & category aware =====================
    public function fetchNewsAPI(string $category = 'top', string $country = 'US'): array
    {
        // Tunables for SPEED
        $totalSources   = 80;   // not critical (GN generates 1 feed), kept small
        $perFeedItems   = 15;    // fewer per-feed items; we stop early below
        $hoursBack      = 8;    // 8-hour freshness window
        $totalResults   = 20;   // final target
        $descMode       = 'meta'; // 'meta' or 'full'
        $enrichMax      = 8;    // fewer page fetches for descriptions

        $category = strtolower(trim($category ?: 'top'));
        $country  = strtoupper(trim($country ?: 'US'));

        // Build feed list (Google News ONLY)
        $allFeedUrls = $this->n_buildFeeds($category, $country, null);
        shuffle($allFeedUrls);
        $allFeedUrls = array_slice($allFeedUrls, 0, max(1, min($totalSources, count($allFeedUrls))));

        // Pull RSS bodies (cached & resilient)
        $responses = $this->n_fetchFeedsPool($allFeedUrls, 'application/rss+xml, application/atom+xml, application/xml;q=0.9, */*;q=0.8');

        $topicTerms = $this->n_categoryKeywords($category);
        $items = [];
        foreach ($allFeedUrls as $feedUrl) {
            $xml = $responses[$feedUrl] ?? null;
            if (!$xml) continue;
            $isGN = str_contains($feedUrl, 'news.google.com');
            $parsed = $this->n_parseFeed($xml, $feedUrl, $isGN ? [] : $topicTerms, $perFeedItems);
            $items = array_merge($items, $parsed);

            // ⚡ Stop early when we already have enough candidates (buffer for de-dupe)
            if (count($items) >= $totalResults * 2) break;
        }
        if (!$items) return [];

        // Freshness
        $cutoff = now()->subHours($hoursBack);
        $items = array_values(array_filter($items, fn($it) => $this->n_ts($it['date_dt'] ?? null) >= $this->n_ts($cutoff)));
        if (!$items) return [];

        // De-dup
        $seen = [];
        $dedup = [];
        foreach ($items as $it) {
            $key = strtolower(preg_replace('~\W+~u', ' ', $it['title'])) . '|' . $this->n_domain($it['url']);
            if (isset($seen[$key])) continue;
            $seen[$key] = 1;
            $dedup[] = $it;
        }

        // Sort & trim
        usort($dedup, fn($a, $b) => $this->n_ts($b['date_dt'] ?? null) <=> $this->n_ts($a['date_dt'] ?? null));
        $dedup = array_slice($dedup, 0, $totalResults);

        // Enrich top N with page meta/full text (kept small for speed)
        $head = array_slice($dedup, 0, $enrichMax);
        $tail = array_slice($dedup, $enrichMax);

        $enriched = [];
        if (!empty($head)) {
            $descResponses = Http::pool(function ($pool) use ($head) {
                foreach ($head as $idx => $it) {
                    $pool->as("desc_{$idx}")
                        ->withHeaders(['User-Agent' => $this->newsUserAgent])
                        ->withOptions([
                            'allow_redirects' => true,
                            'connect_timeout' => 3,
                            'timeout'         => $this->newsTimeout,
                            'force_ip_resolve' => 'v4',
                        ])
                        ->retry(1, 200)
                        ->get($it['url']);
                }
            });

            foreach ($head as $idx => $it) {
                $r = $descResponses["desc_{$idx}"] ?? null;
                $desc = '';
                if ($this->n_responseOk($r)) {
                    if ($descMode === 'full') {
                        $desc = $this->n_fullTextFromBody($r->body(), $it['url']) ?: '';
                        if (!$desc) $desc = $this->n_metaDescriptionFromBody($r->body(), $it['url']);
                    } else {
                        $desc = $this->n_metaDescriptionFromBody($r->body(), $it['url']);
                        if (!$desc) $desc = $this->n_fullTextFromBody($r->body(), $it['url']);
                    }
                }
                if (!$desc) {
                    $desc = $descMode === 'full' ? $this->n_fullText($it['url']) : $this->n_metaDescription($it['url']);
                }
                $desc = trim($desc);
                if (str_starts_with(strtolower($desc), 'comprehensive up-to-date news coverage')) $desc = '';
                $it['description'] = $desc ?: ($it['summary'] ?? '');
                $enriched[] = $it;
            }
        }
        foreach ($tail as $it) {
            $it['description'] = $it['summary'] ?? '';
            $enriched[] = $it;
        }

        usort($enriched, fn($a, $b) => $this->n_ts($b['date_dt'] ?? null) <=> $this->n_ts($a['date_dt'] ?? null));

        $out = [];
        foreach ($enriched as $it) {
            $out[] = [
                'title'       => $it['title'],
                'description' => $it['description'] ?? '',
                'url'         => $it['url'],
                'source'      => $it['source'],
                'date'        => $this->n_iso($it['date_dt'] ?? null),
            ];
        }
        return $out;
    }

    /** Keyword search with country context (query honored; category optional) */
    protected function searchNewsAPI(string $query, ?string $country = 'US', ?string $category = null): array
    {
        // OPTIMIZED settings for faster response
        $totalSources   = 15;  // Reduced from 40
        $perFeedItems   = 3;   // Reduced from 6
        $hoursBack      = 24;  // Increased from 12 to get more results
        $totalResults   = 5;   // Reduced from 8
        $descMode       = 'meta';
        $enrichMax      = 3;   // Reduced from 6

        $country = strtoupper(trim($country ?: 'US'));
        $allFeedUrls = $this->n_buildFeeds($category, $country, $query);
        shuffle($allFeedUrls);
        $allFeedUrls = array_slice($allFeedUrls, 0, max(1, min($totalSources, count($allFeedUrls))));

        $responses = $this->n_fetchFeedsPool($allFeedUrls, 'application/rss+xml, application/atom+xml, application/xml;q=0.9, */*;q=0.8');

        $topicTerms = $this->n_categoryKeywords($category);
        $items = [];
        foreach ($allFeedUrls as $feedUrl) {
            $xml = $responses[$feedUrl] ?? null;
            if (!$xml) continue;
            $isGN = str_contains($feedUrl, 'news.google.com');
            $parsed = $this->n_parseFeed($xml, $feedUrl, $isGN ? [] : $topicTerms, $perFeedItems);
            $items = array_merge($items, $parsed);

            // ⚡ Early stop
            if (count($items) >= $totalResults * 2) break;
        }
        if (!$items) return [];

        $cutoff = now()->subHours($hoursBack);
        $items = array_values(array_filter($items, fn($it) => $this->n_ts($it['date_dt'] ?? null) >= $this->n_ts($cutoff)));
        if (!$items) return [];

        $seen = [];
        $dedup = [];
        foreach ($items as $it) {
            $key = strtolower(preg_replace('~\W+~u', ' ', $it['title'])) . '|' . $this->n_domain($it['url']);
            if (isset($seen[$key])) continue;
            $seen[$key] = 1;
            $dedup[] = $it;
        }

        usort($dedup, fn($a, $b) => $this->n_ts($b['date_dt'] ?? null) <=> $this->n_ts($a['date_dt'] ?? null));
        $dedup = array_slice($dedup, 0, $totalResults);

        $head = array_slice($dedup, 0, $enrichMax);
        $tail = array_slice($dedup, $enrichMax);

        $enriched = [];
        if (!empty($head)) {
            $descResponses = Http::pool(function ($pool) use ($head) {
                foreach ($head as $idx => $it) {
                    $pool->as("desc_{$idx}")
                        ->withHeaders(['User-Agent' => $this->newsUserAgent])
                        ->withOptions([
                            'allow_redirects' => true,
                            'connect_timeout' => 3,
                            'timeout'         => $this->newsTimeout,
                            'force_ip_resolve' => 'v4',
                        ])
                        ->retry(1, 200)
                        ->get($it['url']);
                }
            });

            foreach ($head as $idx => $it) {
                $r = $descResponses["desc_{$idx}"] ?? null;
                $desc = '';
                if ($this->n_responseOk($r)) {
                    if ($descMode === 'full') {
                        $desc = $this->n_fullTextFromBody($r->body(), $it['url']) ?: '';
                        if (!$desc) $desc = $this->n_metaDescriptionFromBody($r->body(), $it['url']);
                    } else {
                        $desc = $this->n_metaDescriptionFromBody($r->body(), $it['url']);
                        if (!$desc) $desc = $this->n_fullTextFromBody($r->body(), $it['url']);
                    }
                }
                if (!$desc) $desc = $this->n_fullText($it['url']);
                if (str_starts_with(strtolower($desc), 'comprehensive up-to-date news coverage')) $desc = '';
                $it['description'] = $desc ?: ($it['summary'] ?? '');
                $enriched[] = $it;
            }
        }
        foreach ($tail as $it) {
            $it['description'] = $it['summary'] ?? '';
            $enriched[] = $it;
        }

        usort($enriched, fn($a, $b) => $this->n_ts($b['date_dt'] ?? null) <=> $this->n_ts($a['date_dt'] ?? null));

        return array_map(fn($it) => [
            'title'       => $it['title'],
            'description' => $it['description'] ?? '',
            'url'         => $it['url'],
            'source'      => $it['source'],
            'date'        => $this->n_iso($it['date_dt'] ?? null),
        ], $enriched);
    }

    // ===================== Notification helper =====================
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

            // Use Mail facade if available, otherwise just log
            if (class_exists('\Illuminate\Support\Facades\Mail')) {
                \Illuminate\Support\Facades\Mail::mailer(config('mail.default', 'smtp'))->raw($body, function ($m) use ($recipient, $subject) {
                    $m->to($recipient)->subject($subject);
                });
                Log::info("notifyNewsApiTopHeadlinesLimit: email dispatched to {$recipient}");
            } else {
                Log::info("notifyNewsApiTopHeadlinesLimit: email would be sent to {$recipient} - Mail facade not available");
            }
        } catch (\Throwable $e) {
            Log::error("notifyNewsApiTopHeadlinesLimit failed: " . $e->getMessage());
        }
    }

    // ===================== Trending Topics Integration =====================
    protected function fetchTrendingTopics(string $country = 'US', int $limit = 10): array
    {
        try {
            // Use Google News trending topics as a fallback
            $trendsUrl = "https://news.google.com/rss?hl=en&gl=" . strtoupper($country) . "&ceid=" . strtoupper($country) . ":en";
            
            $response = Http::withHeaders([
                'User-Agent' => $this->newsUserAgent,
                'Accept' => 'application/rss+xml, application/xml, text/xml, */*',
            ])
            ->withOptions([
                'timeout' => 10,
                'connect_timeout' => 5,
            ])
            ->get($trendsUrl);

            if (!$response->successful()) {
                Log::warning("Trending topics fetch failed: " . $response->status());
                return $this->getFallbackTrendingTopics($country, $limit);
            }

            $xml = $response->body();
            if (!$xml) return $this->getGPTTrendingTopics($country, $limit);

            $trends = [];
            $rss = @simplexml_load_string($xml);
            if (!$rss || !isset($rss->channel->item)) {
                return $this->getGPTTrendingTopics($country, $limit);
            }

            $count = 0;
            foreach ($rss->channel->item as $item) {
                if ($count >= $limit) break;
                
                $title = (string)($item->title ?? '');
                $description = (string)($item->description ?? '');
                
                if ($title && !empty(trim($title))) {
                    // Extract key terms from the title
                    $terms = $this->extractKeyTerms($title);
                    
                    foreach ($terms as $term) {
                        if (strlen($term) > 3 && !in_array(strtolower($term), ['the', 'and', 'for', 'are', 'but', 'not', 'you', 'all', 'can', 'had', 'her', 'was', 'one', 'our', 'out', 'day', 'get', 'has', 'him', 'his', 'how', 'man', 'new', 'now', 'old', 'see', 'two', 'way', 'who', 'boy', 'did', 'its', 'let', 'put', 'say', 'she', 'too', 'use'])) {
                            $trends[] = [
                                'term' => $term,
                                'description' => trim($description),
                                'source' => 'Google News Trends',
                                'trending_score' => $limit - $count, // Higher score for higher ranking
                                'original_title' => $title,
                            ];
                            break; // Only take the first good term from each title
                        }
                    }
                    $count++;
                }
            }

            return array_slice($trends, 0, $limit);
        } catch (\Exception $e) {
            Log::error("Trending topics fetch error: " . $e->getMessage());
            return $this->getGPTTrendingTopics($country, $limit);
        }
    }

    // ===================== Extract Key Terms from Title =====================
    protected function extractKeyTerms(string $title): array
    {
        // Remove common words and extract meaningful terms
        $title = preg_replace('/[^\w\s]/', ' ', $title);
        $words = array_filter(explode(' ', strtolower($title)));
        
        // Remove common stop words
        $stopWords = ['the', 'and', 'for', 'are', 'but', 'not', 'you', 'all', 'can', 'had', 'her', 'was', 'one', 'our', 'out', 'day', 'get', 'has', 'him', 'his', 'how', 'man', 'new', 'now', 'old', 'see', 'two', 'way', 'who', 'boy', 'did', 'its', 'let', 'put', 'say', 'she', 'too', 'use', 'with', 'have', 'this', 'will', 'your', 'from', 'they', 'know', 'want', 'been', 'good', 'much', 'some', 'time', 'very', 'when', 'come', 'here', 'just', 'like', 'long', 'make', 'many', 'over', 'such', 'take', 'than', 'them', 'well', 'were'];
        
        $terms = array_filter($words, function($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 3;
        });
        
        return array_values($terms);
    }

    // ===================== Random Trending Topics (Global) =====================
    protected function getRandomTrendingTopics(int $limit): array
    {
        if (!$this->openai_api_key) {
            return $this->getFallbackRandomTopics($limit);
        }

        try {
            $prompt = "Generate {$limit} random trending topics that would be relevant for LinkedIn content creation. Mix business, technology, innovation, and professional development topics from around the world. Format as JSON array of strings.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openai_api_key,
                'Content-Type' => 'application/json',
            ])
            ->timeout(15)
            ->post($this->openai_api_base . '/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a global trend analysis expert. Generate diverse trending topics for professional content creation from around the world.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.8
            ]);

            if (!$response->successful()) {
                Log::warning("GPT random trending topics failed: " . $response->status());
                return $this->getFallbackRandomTopics($limit);
            }

            $data = $response->json();
            $gptResponse = $data['choices'][0]['message']['content'] ?? '';
            
            return $this->parseGPTTrendingResponse($gptResponse, 'global', $limit);
        } catch (\Exception $e) {
            Log::error("GPT random trending topics error: " . $e->getMessage());
            return $this->getFallbackRandomTopics($limit);
        }
    }

    // ===================== Fallback Random Topics =====================
    protected function getFallbackRandomTopics(int $limit): array
    {
        $allTopics = [
            'artificial intelligence', 'climate change', 'renewable energy', 'space exploration', 
            'cryptocurrency', 'electric vehicles', 'sustainable technology', 'quantum computing',
            'biotechnology', 'cybersecurity', 'blockchain', 'machine learning', 'data science',
            'green energy', 'smart cities', 'digital transformation', 'fintech', 'healthtech',
            'remote work', 'workplace culture', 'leadership', 'innovation', 'startup ecosystem',
            'global economy', 'supply chain', 'e-commerce', 'social media trends', 'privacy',
            'automation', 'robotics', 'augmented reality', 'virtual reality', 'metaverse',
            'sustainable business', 'ESG investing', 'carbon neutrality', 'circular economy',
            'diversity and inclusion', 'mental health', 'work-life balance', 'employee engagement',
            'customer experience', 'digital marketing', 'content strategy', 'brand building',
            'market trends', 'consumer behavior', 'retail innovation', 'logistics optimization'
        ];

        $randomTopics = array_rand(array_flip($allTopics), min($limit, count($allTopics)));
        if (!is_array($randomTopics)) {
            $randomTopics = [$randomTopics];
        }
        
        $trends = [];
        for ($i = 0; $i < count($randomTopics); $i++) {
            $trends[] = [
                'term' => $randomTopics[$i],
                'description' => 'Global trending topic',
                'source' => 'Random Trends',
                'trending_score' => $limit - $i,
            ];
        }

        return $trends;
    }

    // ===================== Genre and Field Filtering =====================
    protected function applyGenreFieldFilters(array $articles, string $genre = '', string $field = ''): array
    {
        if (empty($genre) && empty($field)) {
            return $articles;
        }

        return array_filter($articles, function($article) use ($genre, $field) {
            $title = strtolower($article['title'] ?? '');
            $description = strtolower($article['description'] ?? '');
            $content = $title . ' ' . $description;
            
            $genreMatch = true;
            $fieldMatch = true;
            
            if (!empty($genre)) {
                $genreKeywords = $this->getGenreKeywords($genre);
                $genreMatch = false;
                foreach ($genreKeywords as $keyword) {
                    if (str_contains($content, strtolower($keyword))) {
                        $genreMatch = true;
                        break;
                    }
                }
            }
            
            if (!empty($field)) {
                $fieldKeywords = $this->getFieldKeywords($field);
                $fieldMatch = false;
                foreach ($fieldKeywords as $keyword) {
                    if (str_contains($content, strtolower($keyword))) {
                        $fieldMatch = true;
                        break;
                    }
                }
            }
            
            // If both genre and field are specified, require at least one to match
            // If only one is specified, require that one to match
            if (!empty($genre) && !empty($field)) {
                return $genreMatch || $fieldMatch;
            } else {
                return $genreMatch || $fieldMatch;
            }
        });
    }

    // ===================== Genre Keywords =====================
    protected function getGenreKeywords(string $genre): array
    {
        $genreMap = [
            'business' => ['business', 'corporate', 'company', 'enterprise', 'market', 'economy', 'finance', 'investment', 'revenue', 'profit', 'startup', 'entrepreneur', 'leadership', 'management', 'strategy', 'growth', 'sales', 'marketing', 'brand', 'industry'],
            'technology' => ['technology', 'tech', 'software', 'hardware', 'digital', 'innovation', 'startup', 'app', 'platform', 'system', 'development', 'programming', 'coding', 'data', 'cloud', 'mobile', 'web', 'internet', 'cyber', 'automation'],
            'science' => ['science', 'research', 'study', 'discovery', 'experiment', 'analysis', 'data', 'scientific', 'laboratory', 'theory', 'physics', 'chemistry', 'biology', 'mathematics', 'engineering', 'innovation', 'breakthrough', 'academic', 'university', 'institute'],
            'health' => ['health', 'medical', 'healthcare', 'medicine', 'treatment', 'therapy', 'wellness', 'fitness', 'nutrition', 'disease', 'doctor', 'hospital', 'patient', 'clinical', 'pharmaceutical', 'mental health', 'public health', 'epidemic', 'vaccine', 'diagnosis'],
            'education' => ['education', 'learning', 'teaching', 'school', 'university', 'student', 'academic', 'knowledge', 'training', 'course', 'curriculum', 'teacher', 'professor', 'scholarship', 'degree', 'certification', 'skill', 'literacy', 'pedagogy', 'e-learning'],
            'entertainment' => ['entertainment', 'movie', 'music', 'game', 'sport', 'celebrity', 'show', 'film', 'concert', 'event', 'television', 'streaming', 'gaming', 'esports', 'theater', 'comedy', 'drama', 'action', 'romance', 'thriller'],
            'politics' => ['politics', 'government', 'policy', 'election', 'vote', 'democracy', 'law', 'regulation', 'minister', 'president', 'parliament', 'congress', 'senate', 'legislation', 'diplomacy', 'international', 'foreign policy', 'campaign', 'candidate', 'political party'],
            'environment' => ['environment', 'climate', 'sustainability', 'green', 'renewable', 'carbon', 'pollution', 'conservation', 'ecology', 'nature', 'global warming', 'emissions', 'solar', 'wind', 'energy', 'biodiversity', 'deforestation', 'ocean', 'wildlife', 'recycling'],
            'sports' => ['sports', 'athlete', 'team', 'championship', 'tournament', 'olympics', 'football', 'basketball', 'soccer', 'tennis', 'golf', 'baseball', 'hockey', 'cricket', 'rugby', 'swimming', 'running', 'cycling', 'fitness', 'training'],
            'lifestyle' => ['lifestyle', 'fashion', 'beauty', 'travel', 'food', 'cooking', 'restaurant', 'recipe', 'culture', 'art', 'design', 'architecture', 'interior', 'home', 'family', 'parenting', 'relationship', 'wedding', 'celebration', 'hobby'],
            'finance' => ['finance', 'banking', 'investment', 'stock', 'market', 'trading', 'cryptocurrency', 'bitcoin', 'ethereum', 'portfolio', 'wealth', 'retirement', 'insurance', 'loan', 'mortgage', 'credit', 'budget', 'savings', 'financial planning', 'economy'],
            'real estate' => ['real estate', 'property', 'housing', 'mortgage', 'rent', 'apartment', 'house', 'commercial', 'residential', 'development', 'construction', 'architecture', 'interior design', 'home improvement', 'property management', 'investment', 'market', 'valuation', 'broker'],
            'automotive' => ['automotive', 'car', 'vehicle', 'automobile', 'truck', 'motorcycle', 'electric vehicle', 'tesla', 'hybrid', 'autonomous', 'driving', 'transportation', 'mobility', 'fuel', 'engine', 'manufacturing', 'dealership', 'insurance', 'maintenance'],
            'media' => ['media', 'journalism', 'news', 'broadcasting', 'publishing', 'social media', 'content', 'blog', 'podcast', 'youtube', 'instagram', 'facebook', 'twitter', 'linkedin', 'influencer', 'viral', 'trending', 'viral', 'engagement', 'audience'],
            'retail' => ['retail', 'shopping', 'e-commerce', 'amazon', 'store', 'brand', 'product', 'customer', 'sales', 'marketing', 'advertising', 'promotion', 'discount', 'inventory', 'supply chain', 'logistics', 'delivery', 'shipping', 'customer service', 'experience']
        ];

        return $genreMap[strtolower($genre)] ?? [];
    }

    // ===================== Field Keywords =====================
    protected function getFieldKeywords(string $field): array
    {
        $fieldMap = [
            'artificial intelligence' => ['ai', 'artificial intelligence', 'machine learning', 'deep learning', 'neural network', 'algorithm', 'automation', 'robotics', 'chatgpt', 'openai', 'generative ai', 'llm', 'nlp', 'computer vision', 'intelligent', 'smart', 'cognitive', 'gpt', 'claude', 'bard', 'copilot', 'assistant', 'predictive', 'analytics'],
            'blockchain' => ['blockchain', 'cryptocurrency', 'bitcoin', 'ethereum', 'nft', 'defi', 'web3', 'crypto', 'distributed ledger', 'digital currency', 'token', 'smart contract', 'solana', 'cardano', 'polygon', 'binance', 'coinbase', 'metamask', 'wallet', 'mining'],
            'cybersecurity' => ['cybersecurity', 'security', 'hacking', 'privacy', 'data protection', 'encryption', 'firewall', 'malware', 'breach', 'threat', 'vulnerability', 'secure', 'protection', 'ransomware', 'phishing', 'zero-day', 'penetration testing', 'compliance', 'gdpr', 'iso27001'],
            'fintech' => ['fintech', 'financial technology', 'digital banking', 'payment', 'mobile money', 'insurtech', 'wealthtech', 'regtech', 'banking', 'finance', 'digital payment', 'fintech', 'stripe', 'paypal', 'square', 'venmo', 'robinhood', 'chime', 'revolut', 'nubank'],
            'healthtech' => ['healthtech', 'digital health', 'telemedicine', 'healthcare technology', 'medical device', 'health app', 'wearable', 'diagnosis', 'healthcare', 'medical', 'health', 'medicine', 'epic', 'cerner', 'teladoc', 'fitbit', 'apple watch', 'electronic health records', 'ehr'],
            'edtech' => ['edtech', 'education technology', 'online learning', 'e-learning', 'learning management', 'educational software', 'virtual classroom', 'education', 'learning', 'teaching', 'academic', 'coursera', 'udemy', 'khan academy', 'zoom', 'google classroom', 'canvas', 'blackboard', 'moodle'],
            'cleantech' => ['cleantech', 'clean technology', 'renewable energy', 'solar', 'wind', 'battery', 'energy storage', 'green technology', 'sustainability', 'climate', 'environment', 'green', 'tesla', 'solar panel', 'wind turbine', 'electric grid', 'carbon neutral', 'net zero', 'esg'],
            'biotech' => ['biotech', 'biotechnology', 'pharmaceutical', 'drug development', 'genetics', 'biomedical', 'life sciences', 'therapeutics', 'biology', 'genetic', 'pharma', 'mrna', 'vaccine', 'clinical trial', 'fda approval', 'gene therapy', 'precision medicine', 'crispr', 'synthetic biology'],
            'cloud computing' => ['cloud', 'cloud computing', 'aws', 'azure', 'google cloud', 'serverless', 'microservices', 'kubernetes', 'docker', 'containers', 'saas', 'paas', 'iaas', 'hybrid cloud', 'multi-cloud', 'edge computing', 'devops', 'ci/cd'],
            'data science' => ['data science', 'big data', 'analytics', 'machine learning', 'statistics', 'python', 'r', 'sql', 'tableau', 'power bi', 'data visualization', 'predictive modeling', 'data mining', 'business intelligence', 'data warehouse', 'etl', 'apache spark', 'hadoop'],
            'mobile development' => ['mobile', 'mobile development', 'ios', 'android', 'react native', 'flutter', 'swift', 'kotlin', 'java', 'mobile app', 'app store', 'google play', 'cross-platform', 'native', 'hybrid', 'progressive web app', 'pwa'],
            'web development' => ['web development', 'frontend', 'backend', 'full stack', 'javascript', 'react', 'vue', 'angular', 'node.js', 'html', 'css', 'php', 'python', 'django', 'flask', 'express', 'api', 'rest', 'graphql'],
            'e-commerce' => ['e-commerce', 'online shopping', 'amazon', 'shopify', 'woocommerce', 'magento', 'bigcommerce', 'online store', 'digital marketplace', 'payment gateway', 'shopping cart', 'inventory management', 'order fulfillment', 'customer experience'],
            'social media' => ['social media', 'facebook', 'instagram', 'twitter', 'linkedin', 'tiktok', 'youtube', 'snapchat', 'pinterest', 'social networking', 'content creation', 'influencer marketing', 'viral', 'engagement', 'community management', 'social commerce'],
            'gaming' => ['gaming', 'video games', 'esports', 'game development', 'unity', 'unreal engine', 'mobile gaming', 'pc gaming', 'console gaming', 'nintendo', 'playstation', 'xbox', 'steam', 'twitch', 'streaming', 'virtual reality', 'vr', 'augmented reality', 'ar'],
            'automotive tech' => ['automotive', 'autonomous vehicles', 'self-driving', 'tesla', 'electric vehicle', 'ev', 'hybrid', 'connected car', 'iot', 'sensors', 'lidar', 'radar', 'camera', 'fleet management', 'ride sharing', 'uber', 'lyft', 'mobility as a service'],
            'space technology' => ['space', 'space technology', 'spacex', 'nasa', 'satellite', 'rocket', 'launch', 'mars', 'moon', 'space exploration', 'commercial space', 'space tourism', 'orbital', 'constellation', 'starlink', 'blue origin', 'virgin galactic'],
            'agriculture tech' => ['agriculture', 'agtech', 'precision agriculture', 'farming', 'crop monitoring', 'drones', 'sensors', 'irrigation', 'livestock', 'food production', 'sustainable farming', 'vertical farming', 'hydroponics', 'smart farming', 'farm management'],
            'logistics' => ['logistics', 'supply chain', 'shipping', 'delivery', 'warehouse', 'inventory', 'fulfillment', 'last mile', 'freight', 'transportation', 'tracking', 'optimization', 'amazon logistics', 'fedex', 'ups', 'dhl', 'route optimization'],
            'real estate tech' => ['real estate tech', 'proptech', 'property technology', 'real estate', 'property management', 'virtual tours', 'ar', 'vr', 'smart home', 'iot', 'property valuation', 'real estate platform', 'zillow', 'redfin', 'compass', 'opendoor']
        ];

        return $fieldMap[strtolower($field)] ?? [];
    }

    // ===================== GPT-Enhanced Trending Topics =====================
    protected function getGPTTrendingTopics(string $country, int $limit): array
    {
        if (!$this->openai_api_key) {
            return $this->getFallbackTrendingTopics($country, $limit);
        }

        try {
            $prompt = "Generate {$limit} trending topics for {$country} that would be relevant for LinkedIn content creation. Focus on business, technology, innovation, and professional development topics. Format as JSON array of strings.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openai_api_key,
                'Content-Type' => 'application/json',
            ])
            ->timeout(15)
            ->post($this->openai_api_base . '/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a trend analysis expert. Generate relevant trending topics for professional content creation.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7
            ]);

            if (!$response->successful()) {
                Log::warning("GPT trending topics failed: " . $response->status());
                return $this->getFallbackTrendingTopics($country, $limit);
            }

            $data = $response->json();
            $gptResponse = $data['choices'][0]['message']['content'] ?? '';
            
            return $this->parseGPTTrendingResponse($gptResponse, $country, $limit);
        } catch (\Exception $e) {
            Log::error("GPT trending topics error: " . $e->getMessage());
            return $this->getFallbackTrendingTopics($country, $limit);
        }
    }

    // ===================== Parse GPT Trending Response =====================
    protected function parseGPTTrendingResponse(string $gptResponse, string $country, int $limit): array
    {
        try {
            // Extract JSON array from response
            $jsonStart = strpos($gptResponse, '[');
            $jsonEnd = strrpos($gptResponse, ']');
            
            if ($jsonStart === false || $jsonEnd === false) {
                return $this->getFallbackTrendingTopics($country, $limit);
            }
            
            $jsonString = substr($gptResponse, $jsonStart, $jsonEnd - $jsonStart + 1);
            $topics = json_decode($jsonString, true);
            
            if (!is_array($topics)) {
                return $this->getFallbackTrendingTopics($country, $limit);
            }
            
            $trends = [];
            for ($i = 0; $i < min($limit, count($topics)); $i++) {
                $trends[] = [
                    'term' => $topics[$i],
                    'description' => 'AI-generated trending topic for ' . $country,
                    'source' => 'GPT Trends',
                    'trending_score' => $limit - $i,
                ];
            }

            return $trends;
        } catch (\Exception $e) {
            Log::error("GPT trending response parsing error: " . $e->getMessage());
            return $this->getFallbackTrendingTopics($country, $limit);
        }
    }

    // ===================== Google Trends API Integration =====================
    protected function fetchGoogleTrendsTopics(string $country = 'US', int $limit = 10): array
    {
        try {
            // If no API key is configured, fall back to fallback topics
            if (empty($this->google_trends_api_key) || $this->google_trends_api_key === 'your_scrapingdog_api_key_here') {
                Log::info("Google Trends API key not configured, using fallback topics");
                return $this->getFallbackTrendingTopics($country, $limit);
            }

            // Use Google Trends API to get real trending topics
            $response = Http::timeout(5)->get($this->google_trends_api_url, [
                'api_key' => $this->google_trends_api_key,
                'data_type' => 'RELATED_QUERIES',
                'geo' => $country,
                'date' => 'now 7-d', // Past 7 days
                'gprop' => 'news', // Focus on news trends
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract trending queries from the response
                $trendingQueries = [];
                
                if (isset($data['related_queries']['rising'])) {
                    foreach ($data['related_queries']['rising'] as $index => $query) {
                        if ($index >= $limit) break;
                        
                        $trendingQueries[] = [
                            'term' => $query['query'] ?? '',
                            'description' => 'Google Trends rising query',
                            'source' => 'Google Trends',
                            'trending_score' => $limit - $index,
                        ];
                    }
                }
                
                // If we don't have enough rising queries, add top queries
                if (count($trendingQueries) < $limit && isset($data['related_queries']['top'])) {
                    $remaining = $limit - count($trendingQueries);
                    foreach ($data['related_queries']['top'] as $index => $query) {
                        if ($index >= $remaining) break;
                        
                        $trendingQueries[] = [
                            'term' => $query['query'] ?? '',
                            'description' => 'Google Trends top query',
                            'source' => 'Google Trends',
                            'trending_score' => $limit - count($trendingQueries),
                        ];
                    }
                }
                
                if (!empty($trendingQueries)) {
                    Log::info("Fetched " . count($trendingQueries) . " trending topics from Google Trends for " . $country);
                    return $trendingQueries;
                }
            }
            
            Log::warning("Google Trends API request failed or returned no data, falling back to fallback topics");
            return $this->getFallbackTrendingTopics($country, $limit);
            
        } catch (\Exception $e) {
            Log::error("Google Trends API error: " . $e->getMessage());
            return $this->getFallbackTrendingTopics($country, $limit);
        }
    }

    protected function fetchGoogleTrendsRandomTopics(int $limit = 10): array
    {
        try {
            // If no API key is configured, fall back to fallback topics
            if (empty($this->google_trends_api_key) || $this->google_trends_api_key === 'your_scrapingdog_api_key_here') {
                Log::info("Google Trends API key not configured, using fallback topics");
                return $this->getFallbackRandomTopics($limit);
            }

            // Use Google Trends API to get global trending topics
            $response = Http::timeout(5)->get($this->google_trends_api_url, [
                'api_key' => $this->google_trends_api_key,
                'data_type' => 'RELATED_QUERIES',
                'geo' => '', // Global
                'date' => 'now 7-d', // Past 7 days
                'gprop' => 'news', // Focus on news trends
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract trending queries from the response
                $trendingQueries = [];
                
                if (isset($data['related_queries']['rising'])) {
                    foreach ($data['related_queries']['rising'] as $index => $query) {
                        if ($index >= $limit) break;
                        
                        $trendingQueries[] = [
                            'term' => $query['query'] ?? '',
                            'description' => 'Global trending topic',
                            'source' => 'Google Trends',
                            'trending_score' => $limit - $index,
                        ];
                    }
                }
                
                // If we don't have enough rising queries, add top queries
                if (count($trendingQueries) < $limit && isset($data['related_queries']['top'])) {
                    $remaining = $limit - count($trendingQueries);
                    foreach ($data['related_queries']['top'] as $index => $query) {
                        if ($index >= $remaining) break;
                        
                        $trendingQueries[] = [
                            'term' => $query['query'] ?? '',
                            'description' => 'Global trending topic',
                            'source' => 'Google Trends',
                            'trending_score' => $limit - count($trendingQueries),
                        ];
                    }
                }
                
                if (!empty($trendingQueries)) {
                    Log::info("Fetched " . count($trendingQueries) . " global trending topics from Google Trends");
                    return $trendingQueries;
                }
            }
            
            Log::warning("Google Trends API request failed or returned no data, falling back to fallback topics");
            return $this->getFallbackRandomTopics($limit);
            
        } catch (\Exception $e) {
            Log::error("Google Trends API error: " . $e->getMessage());
            return $this->getFallbackRandomTopics($limit);
        }
    }

    // ===================== Fallback Trending Topics =====================
    protected function getFallbackTrendingTopics(string $country, int $limit): array
    {
        // Fallback trending topics based on country and current events
        $fallbackTopics = [
            'US' => [
                'artificial intelligence', 'climate change', 'renewable energy', 'space exploration', 
                'cryptocurrency', 'electric vehicles', 'sustainable technology', 'quantum computing',
                'biotechnology', 'cybersecurity', 'blockchain', 'machine learning', 'data science',
                'green energy', 'smart cities', 'digital transformation', 'fintech', 'healthtech'
            ],
            'GB' => [
                'brexit', 'climate change', 'renewable energy', 'artificial intelligence', 
                'sustainable technology', 'electric vehicles', 'cybersecurity', 'fintech',
                'healthtech', 'edtech', 'cleantech', 'biotech', 'quantum computing'
            ],
            'CA' => [
                'climate change', 'renewable energy', 'artificial intelligence', 'sustainable technology',
                'electric vehicles', 'cybersecurity', 'fintech', 'healthtech', 'cleantech', 'biotech'
            ],
            'AU' => [
                'climate change', 'renewable energy', 'artificial intelligence', 'sustainable technology',
                'electric vehicles', 'cybersecurity', 'fintech', 'healthtech', 'mining technology'
            ]
        ];

        $topics = $fallbackTopics[strtoupper($country)] ?? $fallbackTopics['US'];
        
        $trends = [];
        for ($i = 0; $i < min($limit, count($topics)); $i++) {
            $trends[] = [
                'term' => $topics[$i],
                'description' => 'Trending topic in ' . $country,
                'source' => 'Fallback Trends',
                'trending_score' => $limit - $i,
            ];
        }

        return $trends;
    }

    // ===================== Random News Fetching (Global) =====================
    protected function fetchRandomNews(string $category = 'top', string $genre = '', string $field = ''): array
    {
        $articles = [];
        
        // Use Google Trends API to get real trending topics (reduced for speed)
        $trendingTerms = $this->fetchGoogleTrendsRandomTopics(5); // Reduced from 10 to 5
        Log::info("Fetched " . count($trendingTerms) . " trending terms globally");

        // Fetch news from fewer countries to get faster results
        $countries = ['US', 'GB', 'CA', 'AU', 'DE'];
        $selectedCountries = array_rand(array_flip($countries), 2); // Reduced from 3 to 2
        
        foreach ($trendingTerms as $index => $trend) {
            $term = $trend['term'];
            $trendingScore = $trend['trending_score'] ?? (10 - $index);
            
            // Try different countries for variety
            $country = $selectedCountries[array_rand($selectedCountries)];
            $termArticles = $this->searchNewsAPI($term, $country, $category);
            
            // Add trending metadata to articles
            foreach ($termArticles as &$article) {
                $article['trending_term'] = $term;
                $article['trending_score'] = $trendingScore;
                $article['trending_description'] = $trend['description'] ?? '';
                $article['country'] = $country;
            }
            
            $articles = array_merge($articles, $termArticles);
            
            // Limit to prevent too many results (reduced for speed)
            if (count($articles) >= 10) break; // Reduced from 15 to 10
        }

        // Apply genre and field filtering
        $articles = $this->applyGenreFieldFilters($articles, $genre, $field);
        
        // Skip GPT enhancement to prevent timeout - use simple filtering instead
        $articles = $this->applyNewsFilters($articles, $category);
        
        // Sort by trending score and date
        usort($articles, function($a, $b) {
            $scoreA = $a['trending_score'] ?? 0;
            $scoreB = $b['trending_score'] ?? 0;
            
            if ($scoreA === $scoreB) {
                return strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? '');
            }
            
            return $scoreB <=> $scoreA;
        });

        return array_slice($articles, 0, 15); // Reduced from 20 to 15
    }

    // ===================== Country-Specific News Fetching =====================
    protected function fetchCountrySpecificNews(string $category = 'top', string $country = 'US', string $genre = '', string $field = '', ?string $search = null): array
    {
        $articles = [];

        if ($search) {
            // Direct search for specific country
            $articles = $this->searchNewsAPI($search, $country, $category);
        } else {
            // Use Google Trends API to get real trending topics (reduced for speed)
            $trendingTerms = $this->fetchGoogleTrendsTopics($country, 5); // Reduced from 10 to 5
            Log::info("Fetched " . count($trendingTerms) . " trending terms for " . $country);

            // Fetch news using trending terms
            foreach ($trendingTerms as $index => $trend) {
                $term = $trend['term'];
                $trendingScore = $trend['trending_score'] ?? (5 - $index);
                
                $termArticles = $this->searchNewsAPI($term, $country, $category);
                
                // Add trending metadata to articles
                foreach ($termArticles as &$article) {
                    $article['trending_term'] = $term;
                    $article['trending_score'] = $trendingScore;
                    $article['trending_description'] = $trend['description'] ?? '';
                    $article['country'] = $country;
                }
                
                $articles = array_merge($articles, $termArticles);
                
                // Limit to prevent too many results
                if (count($articles) >= 30) break; // Increased to 30 to ensure at least 15 results after filtering
            }
        }

        // Apply genre and field filtering
        $articles = $this->applyGenreFieldFilters($articles, $genre, $field);
        
        // Skip GPT enhancement to prevent timeout - use simple filtering instead
        $articles = $this->applyNewsFilters($articles, $category);
        
        // Sort by trending score and date
        usort($articles, function($a, $b) {
            $scoreA = $a['trending_score'] ?? 0;
            $scoreB = $b['trending_score'] ?? 0;
            
            if ($scoreA === $scoreB) {
                return strtotime($b['date'] ?? '') <=> strtotime($a['date'] ?? '');
            }
            
            return $scoreB <=> $scoreA;
        });

        return array_slice($articles, 0, 20);
    }

    // ===================== GPT-Powered News Enhancement =====================
    protected function enhanceNewsWithGPT(array $articles, string $category, string $country): array
    {
        if (empty($articles) || !$this->openai_api_key) {
            return $this->applyNewsFilters($articles, $category);
        }

        try {
            // Limit articles to prevent timeout and process only top 10
            $articles = array_slice($articles, 0, 10);
            
            // Use GPT to analyze and enhance news articles
            $enhancedArticles = [];
            $batchSize = 3; // Smaller batch size to prevent timeout
            
            for ($i = 0; $i < count($articles); $i += $batchSize) {
                $batch = array_slice($articles, $i, $batchSize);
                $enhancedBatch = $this->processNewsBatchWithGPT($batch, $category, $country);
                $enhancedArticles = array_merge($enhancedArticles, $enhancedBatch);
                
                // Add small delay to prevent rate limiting
                if ($i + $batchSize < count($articles)) {
                    usleep(500000); // 0.5 second delay
                }
            }

            return $enhancedArticles;
        } catch (\Exception $e) {
            Log::error("GPT news enhancement failed: " . $e->getMessage());
            return $this->applyNewsFilters($articles, $category);
        }
    }

    // ===================== Process News Batch with GPT =====================
    protected function processNewsBatchWithGPT(array $articles, string $category, string $country): array
    {
        try {
            $prompt = $this->buildNewsAnalysisPrompt($articles, $category, $country);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openai_api_key,
                'Content-Type' => 'application/json',
            ])
            ->timeout(15)
            ->post($this->openai_api_base . '/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a news analysis expert. Analyze news articles and provide insights, relevance scores, and enhanced descriptions.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.3
            ]);

            if (!$response->successful()) {
                Log::warning("GPT API request failed: " . $response->status());
                return $this->applyNewsFilters($articles, $category);
            }

            $data = $response->json();
            $gptResponse = $data['choices'][0]['message']['content'] ?? '';
            
            return $this->parseGPTNewsResponse($articles, $gptResponse);
        } catch (\Exception $e) {
            Log::error("GPT batch processing error: " . $e->getMessage());
            return $this->applyNewsFilters($articles, $category);
        }
    }

    // ===================== Build News Analysis Prompt =====================
    protected function buildNewsAnalysisPrompt(array $articles, string $category, string $country): string
    {
        $articlesText = '';
        foreach ($articles as $index => $article) {
            $articlesText .= "Article " . ($index + 1) . ":\n";
            $articlesText .= "Title: " . ($article['title'] ?? 'N/A') . "\n";
            $articlesText .= "Description: " . ($article['description'] ?? 'N/A') . "\n";
            $articlesText .= "Source: " . ($article['source'] ?? 'N/A') . "\n";
            $articlesText .= "Date: " . ($article['date'] ?? 'N/A') . "\n";
            $articlesText .= "Trending Term: " . ($article['trending_term'] ?? 'N/A') . "\n\n";
        }

        return "Analyze these news articles for the category '{$category}' in {$country}:

{$articlesText}

For each article, provide:
1. Relevance score (1-10) for the category '{$category}'
2. Enhanced description (2-3 sentences)
3. Key insights or trends
4. LinkedIn post potential (1-10)

Format your response as JSON:
{
  \"articles\": [
    {
      \"index\": 1,
      \"relevance_score\": 8,
      \"enhanced_description\": \"Enhanced description here\",
      \"key_insights\": \"Key insights here\",
      \"linkedin_potential\": 7
    }
  ]
}";
    }

    // ===================== Parse GPT News Response =====================
    protected function parseGPTNewsResponse(array $articles, string $gptResponse): array
    {
        try {
            // Extract JSON from GPT response
            $jsonStart = strpos($gptResponse, '{');
            $jsonEnd = strrpos($gptResponse, '}');
            
            if ($jsonStart === false || $jsonEnd === false) {
                return $this->applyNewsFilters($articles, 'top');
            }
            
            $jsonString = substr($gptResponse, $jsonStart, $jsonEnd - $jsonStart + 1);
            $gptData = json_decode($jsonString, true);
            
            if (!$gptData || !isset($gptData['articles'])) {
                return $this->applyNewsFilters($articles, 'top');
            }
            
            $enhancedArticles = [];
            foreach ($gptData['articles'] as $gptArticle) {
                $index = $gptArticle['index'] - 1; // Convert to 0-based index
                
                if (isset($articles[$index])) {
                    $article = $articles[$index];
                    $article['gpt_relevance_score'] = $gptArticle['relevance_score'] ?? 5;
                    $article['gpt_enhanced_description'] = $gptArticle['enhanced_description'] ?? $article['description'];
                    $article['gpt_key_insights'] = $gptArticle['key_insights'] ?? '';
                    $article['gpt_linkedin_potential'] = $gptArticle['linkedin_potential'] ?? 5;
                    
                    // Only include articles with relevance score >= 6
                    if ($article['gpt_relevance_score'] >= 6) {
                        $enhancedArticles[] = $article;
                    }
                }
            }
            
            // Sort by GPT relevance score
            usort($enhancedArticles, function($a, $b) {
                return ($b['gpt_relevance_score'] ?? 0) <=> ($a['gpt_relevance_score'] ?? 0);
            });
            
            return $enhancedArticles;
        } catch (\Exception $e) {
            Log::error("GPT response parsing error: " . $e->getMessage());
            return $this->applyNewsFilters($articles, 'top');
        }
    }

    // ===================== Apply News Filters (Fallback) =====================
    protected function applyNewsFilters(array $articles, string $category): array
    {
        if (empty($articles)) return $articles;

        // Category-based filtering
        $categoryKeywords = $this->n_categoryKeywords($category);
        if (!empty($categoryKeywords)) {
            $articles = array_filter($articles, function($article) use ($categoryKeywords) {
                $title = strtolower($article['title'] ?? '');
                $description = strtolower($article['description'] ?? '');
                $content = $title . ' ' . $description;
                
                foreach ($categoryKeywords as $keyword) {
                    if (str_contains($content, strtolower($keyword))) {
                        return true;
                    }
                }
                return false;
            });
        }

        // Remove duplicates based on title similarity
        $unique = [];
        $seen = [];
        
        foreach ($articles as $article) {
            $title = $article['title'] ?? '';
            $key = strtolower(preg_replace('~\W+~u', ' ', $title));
            
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $article;
            }
        }

        return $unique;
    }

    // ===================== GPT Content Generation =====================
    public function generateContent(Request $request)
    {
        $data = $request->json()->all();
        $article = $data['article'] ?? null;
        $contentType = $data['content_type'] ?? 'linkedin_post';
        $tone = $data['tone'] ?? 'professional';
        $length = $data['length'] ?? 'medium';

        if (!$article || !$this->openai_api_key) {
            return response()->json(['error' => 'Article data and OpenAI key required'], 400);
        }

        try {
            $prompt = $this->buildContentGenerationPrompt($article, $contentType, $tone, $length);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openai_api_key,
                'Content-Type' => 'application/json',
            ])
            ->timeout(15)
            ->post($this->openai_api_base . '/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional content creator specializing in LinkedIn posts, articles, and social media content. Create engaging, professional content based on news articles.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7
            ]);

            if (!$response->successful()) {
                return response()->json(['error' => 'Content generation failed'], 500);
            }

            $data = $response->json();
            $generatedContent = $data['choices'][0]['message']['content'] ?? '';

            return response()->json([
                'content' => $generatedContent,
                'content_type' => $contentType,
                'tone' => $tone,
                'length' => $length,
                'source_article' => $article
            ]);
        } catch (\Exception $e) {
            Log::error("Content generation error: " . $e->getMessage());
            return response()->json(['error' => 'Content generation failed'], 500);
        }
    }

    // ===================== Build Content Generation Prompt =====================
    protected function buildContentGenerationPrompt(array $article, string $contentType, string $tone, string $length): string
    {
        $title = $article['title'] ?? 'N/A';
        $description = $article['description'] ?? 'N/A';
        $source = $article['source'] ?? 'N/A';
        $trendingTerm = $article['trending_term'] ?? 'N/A';
        $insights = $article['gpt_key_insights'] ?? 'N/A';

        $lengthGuidelines = [
            'short' => 'Keep it concise (1-2 paragraphs, under 150 words)',
            'medium' => 'Standard length (2-3 paragraphs, 150-300 words)',
            'long' => 'Detailed content (3-4 paragraphs, 300-500 words)'
        ];

        $toneGuidelines = [
            'professional' => 'Professional, authoritative, and informative',
            'casual' => 'Friendly, approachable, and conversational',
            'enthusiastic' => 'Excited, energetic, and engaging',
            'analytical' => 'Data-driven, analytical, and insightful'
        ];

        $contentTypeGuidelines = [
            'linkedin_post' => 'LinkedIn post with hook, value, and call-to-action',
            'article' => 'Professional article with introduction, body, and conclusion',
            'summary' => 'Executive summary highlighting key points',
            'social_media' => 'Social media post optimized for engagement'
        ];

        return "Create a {$contentType} based on this news article:

Title: {$title}
Description: {$description}
Source: {$source}
Trending Term: {$trendingTerm}
Key Insights: {$insights}

Requirements:
- Content Type: " . ($contentTypeGuidelines[$contentType] ?? $contentTypeGuidelines['linkedin_post']) . "
- Tone: " . ($toneGuidelines[$tone] ?? $toneGuidelines['professional']) . "
- Length: " . ($lengthGuidelines[$length] ?? $lengthGuidelines['medium']) . "

Include:
1. Engaging hook that captures attention
2. Key insights and value for the audience
3. Professional analysis or commentary
4. Relevant hashtags (3-5)
5. Call-to-action if appropriate

Make it relevant for LinkedIn professionals and business audience.";
    }

    // ===================== PUBLIC endpoint used by your UI =====================
    public function generateOptions(Request $request)
    {
        $data     = $request->json()->all();
        $search   = trim($data['q'] ?? '');
        $category = trim($data['category'] ?? 'top');
        $country  = trim($data['country']  ?? '');
        $genre    = trim($data['genre'] ?? '');
        $field    = trim($data['field'] ?? '');

        // Create cache key for this request
        $cacheKey = 'news_options:' . md5($search . '|' . $category . '|' . $country . '|' . $genre . '|' . $field);
        
        // Check cache first (5 minute cache)
        $cached = Cache::get($cacheKey);
        if ($cached) {
            Log::info("Returning cached news options for key: " . substr($cacheKey, 0, 20));
            return response()->json($cached);
        }

        try {
            // If no country specified, fetch random news globally
            if (empty($country)) {
                $articles = $this->fetchRandomNews($category, $genre, $field);
            } else {
                // Fetch country-specific news with filters
                $articles = $this->fetchCountrySpecificNews($category, $country, $genre, $field, $search);
            }

            if (empty($articles)) {
                $this->notifyNewsApiTopHeadlinesLimit([
                    'endpoint' => $search ? 'search' : ($country ? 'country-news' : 'random-news'),
                    'category' => $category,
                    'country'  => $country,
                    'genre'    => $genre,
                    'field'    => $field,
                    'search_q' => $search ?: null,
                ]);
            }

            $response = [
                'trending_topics' => $articles,
                'resources'       => array_values(array_unique(array_map(
                    fn($a) => $a['source'] ?? 'Unknown',
                    $articles
                ))),
                'filters_applied' => [
                    'country' => $country,
                    'category' => $category,
                    'genre' => $genre,
                    'field' => $field,
                    'search' => $search
                ]
            ];

            // Cache the response for 5 minutes
            Cache::put($cacheKey, $response, 300);
            
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("generateOptions error: " . $e->getMessage());
            return response()->json([
                'trending_topics' => [],
                'resources'       => [],
                'error' => 'Failed to fetch news'
            ], 500);
        }
    }
}