<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts to LinkedIn that are pending and whose scheduled time has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $scheduledPosts = ScheduledPost::where('status', 'pending')
            ->where('scheduled_datetime', '<=', $now)
            ->get();

        if ($scheduledPosts->isEmpty()) {
            $this->info("No scheduled posts to publish at this time.");
            return 0;
        }

        foreach ($scheduledPosts as $post) {
            $this->info("Publishing scheduled post ID: " . $post->id);

            // Use the stored user_id and access_token (saved when scheduling the post)
            $person_id = $post->user_id;
            $access_token = $post->access_token;
            $person_urn = "urn:li:person:" . $person_id;

            // Prepare a plain-text version of the post (strip out any media tags)
            $messageWithoutMedia = preg_replace('/<img[^>]*>/i', '', $post->post_text);
            $messageWithoutMedia = preg_replace('/<video[^>]*>.*?<\/video>/i', '', $messageWithoutMedia);
            $plainText = str_replace("<br>", "\n", strip_tags($messageWithoutMedia));
            $plainText = trim($plainText);
            if (empty($plainText)) {
                $plainText = " ";
            }
            if (mb_strlen($plainText) > 8000) {
                $plainText = mb_substr($plainText, 0, 8000);
            }

            $auth_headers = [
                "Authorization" => "Bearer " . $access_token,
                "Content-Type"  => "application/json",
                "X-Restli-Protocol-Version" => "2.0.0"
            ];

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

            $url = "https://api.linkedin.com/v2/ugcPosts";

            try {
                Log::info("Publishing scheduled post ID: " . $post->id);
                $response = Http::withHeaders($auth_headers)->post($url, $payload);
                if ($response->successful()) {
                    Log::info("Scheduled post published successfully, post ID: " . $post->id);
                    $this->info("Scheduled post ID {$post->id} published successfully.");
                    $post->status = 'published';
                    $post->save();
                } else {
                    Log::error("Failed to publish scheduled post ID: " . $post->id . ". Response: " . $response->body());
                    $this->error("Failed to publish scheduled post ID {$post->id}. Response: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Exception publishing scheduled post ID: " . $post->id . ". Error: " . $e->getMessage());
                $this->error("Exception publishing scheduled post ID {$post->id}: " . $e->getMessage());
            }
        }

        return 0;
    }
}
