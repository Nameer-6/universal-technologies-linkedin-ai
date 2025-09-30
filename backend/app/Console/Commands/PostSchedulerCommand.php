<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledPost;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PostSchedulerCommand extends Command
{
    protected $signature = 'posts:schedule-check';
    protected $description = 'Check for any scheduled LinkedIn posts that are due and publish them.';

    public function handle()
    {
        $now = Carbon::now();
        Log::info("PostSchedulerCommand running at: " . $now->toDateTimeString());
        
        $duePosts = ScheduledPost::where('status', 'pending')
            ->where('scheduled_datetime', '<=', $now)
            ->get();

        if ($duePosts->isEmpty()) {
            Log::info("No scheduled posts due at this time.");
            $this->info("No scheduled posts to publish at this time.");
            return 0;
        }

        foreach ($duePosts as $post) {
            Log::info("Attempting to publish scheduled post ID: {$post->id}");
            if ($this->publishToLinkedIn($post)) {
                $post->status = 'published';
                $post->save();
                Log::info("Post ID {$post->id} published successfully.");
                $this->info("Post ID {$post->id} published successfully.");
            } else {
                $post->status = 'failed';
                $post->save();
                Log::error("Post ID {$post->id} failed to publish.");
                $this->error("Post ID {$post->id} failed to publish.");
            }
        }
        return 0;
    }

    protected function publishToLinkedIn(ScheduledPost $post)
    {
        // Prepare plain text by removing media tags.
        $cleanText = preg_replace('/<img[^>]*>/i', '', $post->post_text);
        $cleanText = preg_replace('/<video[^>]*>.*?<\/video>/i', '', $cleanText);
        $cleanText = preg_replace('/<embed[^>]*>/i', '', $cleanText);

        // Convert <br> to \n and strip tags to get final text
        $plainText = trim(strip_tags(str_replace("<br>", "\n", $cleanText)));
        if (empty($plainText)) {
            $plainText = " ";
        }
        if (mb_strlen($plainText) > 8000) {
            $plainText = mb_substr($plainText, 0, 8000);
        }
        
        $person_urn = "urn:li:person:" . $post->user_id;
        $access_token = $post->access_token;
        $url = "https://api.linkedin.com/v2/ugcPosts";

        $headers = [
            "Authorization" => "Bearer " . $access_token,
            "Content-Type"  => "application/json",
            "X-Restli-Protocol-Version" => "2.0.0"
        ];

        // Default payload: text-only post
        $payload = [
            "author" => $person_urn,
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => ["text" => $plainText],
                    "shareMediaCategory" => "NONE"
                ]
            ],
            "visibility" => ["com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC"]
        ];

        // If there's media, register & upload again
        if ($post->media_url && $post->media_type) {
            Log::info("Scheduled post ID {$post->id} contains media. Processing type: {$post->media_type}");
            $mediaBytes = @file_get_contents($post->media_url);
            if ($mediaBytes === false) {
                Log::error("Failed to retrieve media from URL: {$post->media_url} for post ID {$post->id}");
                // Proceed as text-only
            } else {
                // Decide recipe and share category
                if (stripos($post->media_type, "image/") === 0) {
                    $recipe = "urn:li:digitalmediaRecipe:feedshare-image";
                    $mediaDescription = "Image uploaded via AI tool";
                    $mediaTitle = "Uploaded Image";
                    $mediaCategory = "IMAGE";
                } elseif (stripos($post->media_type, "video/") === 0) {
                    $recipe = "urn:li:digitalmediaRecipe:feedshare-video";
                    $mediaDescription = "Video uploaded via AI tool";
                    $mediaTitle = "Uploaded Video";
                    $mediaCategory = "VIDEO";
                }
                // ADDITION: handle documents
                elseif (
                    stripos($post->media_type, "application/pdf") === 0 ||
                    stripos($post->media_type, "application/msword") === 0 ||
                    stripos($post->media_type, "application/vnd.openxmlformats-officedocument.wordprocessingml.document") === 0
                ) {
                    $recipe = "urn:li:digitalmediaRecipe:feedshare-document";
                    $mediaDescription = "Document uploaded via AI tool";
                    $mediaTitle = "Uploaded Document";
                    $mediaCategory = "DOCUMENT";
                } else {
                    Log::error("Unsupported media type {$post->media_type} for post ID {$post->id}");
                    // Proceed as text-only
                    goto publish_text;
                }

                // Register the upload
                $registerPayload = [
                    "registerUploadRequest" => [
                        "recipes" => [$recipe],
                        "owner"   => $person_urn,
                        "serviceRelationships" => [
                            [
                                "relationshipType" => "OWNER",
                                "identifier" => "urn:li:userGeneratedContent"
                            ]
                        ]
                    ]
                ];

                $registerResponse = Http::withHeaders([
                    "Authorization" => "Bearer " . $access_token,
                    "Content-Type"  => "application/json"
                ])->post("https://api.linkedin.com/v2/assets?action=registerUpload", $registerPayload);

                if (!$registerResponse->successful()) {
                    Log::error("Error registering media upload for post ID {$post->id}: " . $registerResponse->body());
                    return false;
                }

                $registerData = $registerResponse->json();
                $uploadUrl = $registerData['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'] ?? null;
                $assetUrn = $registerData['value']['asset'] ?? null;
                if (!$uploadUrl || !$assetUrn) {
                    Log::error("Missing upload URL or asset URN for post ID {$post->id}");
                    return false;
                }

                $uploadResponse = Http::withHeaders([
                    "Authorization" => "Bearer " . $access_token,
                    "Content-Type"  => $post->media_type
                ])->withBody($mediaBytes, $post->media_type)
                  ->put($uploadUrl);

                if (!$uploadResponse->successful()) {
                    Log::error("Error uploading media for post ID {$post->id}: " . $uploadResponse->body());
                    return false;
                }

                // Update the payload with media info
                $payload["specificContent"]["com.linkedin.ugc.ShareContent"]["shareMediaCategory"] = $mediaCategory;
                $payload["specificContent"]["com.linkedin.ugc.ShareContent"]["media"] = [
                    [
                        "status" => "READY",
                        "description" => ["text" => $mediaDescription],
                        "media" => $assetUrn,
                        "title" => ["text" => $mediaTitle]
                    ]
                ];

                Log::info("Media attached for post ID {$post->id} with asset URN: " . $assetUrn);
            }
        }

        publish_text:

        try {
            Log::info("Publishing post ID {$post->id} with payload: " . json_encode($payload));
            $response = Http::withHeaders($headers)->post($url, $payload);
            Log::info("LinkedIn API response status: " . $response->status());
            Log::info("LinkedIn API response body: " . $response->body());
            if ($response->successful() || $response->status() == 201) {
                return true;
            } else {
                Log::error("LinkedIn publish error for post ID {$post->id}: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Exception publishing post ID {$post->id}: " . $e->getMessage());
            return false;
        }
    }
}
