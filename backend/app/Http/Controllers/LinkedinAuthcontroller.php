<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ScheduledPost;

class LinkedinAuthcontroller extends Controller
{
    protected $linkedin_client_id     = "your_linkedin_client_id";
    protected $linkedin_client_secret = "your_linkedin_client_secret";
    protected $linkedin_redirect_uri  = "http://localhost:8000/linkedin-callback";

    public function linkedinLogin(Request $request)
    {   
        // Check for temporary token in Authorization header
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer temp_token_')) {
            $token = str_replace('Bearer temp_token_', '', $authHeader);
            $user = \App\Models\User::find($token);
            if (!$user) {
                return response()->json(["error" => "Invalid token"], 401);
            }

            // Create mock LinkedIn profile data for temporary tokens
            $mockProfile = [
                'person_id' => 'dev_' . $user->id,
                'name' => $user->name,
                'headline' => 'Development User',
                'access_token' => 'mock_access_token_' . time(),
                'profile_picture_url' => null,
            ];

            // Store in session for development
            Session::put('linkedin_profile', $mockProfile);
            Session::put('person_id', $mockProfile['person_id']);

            // Also store in database for consistency
            $linkedinProfile = \App\Models\LinkedinProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'person_id' => $mockProfile['person_id'],
                    'name' => $mockProfile['name'],
                    'headline' => $mockProfile['headline'],
                    'access_token' => $mockProfile['access_token'],
                    'profile_picture_url' => $mockProfile['profile_picture_url'],
                ]
            );

            return response()->json([
                "message" => "Mock LinkedIn profile created successfully",
                "profile" => $mockProfile
            ]);
        }

        // Development mode: Create mock LinkedIn connection
        if (config('app.env') === 'local' || config('app.debug') === true) {
            Log::info("Development mode: Creating mock LinkedIn profile");
            
            $user = Auth::user();
            if (!$user) {
                return response()->json(["error" => "Not logged in"], 401);
            }

            // Create mock LinkedIn profile data
            $mockProfile = [
                'person_id' => 'dev_' . $user->id,
                'name' => $user->name,
                'headline' => 'Development User',
                'access_token' => 'mock_access_token_' . time(),
                'profile_picture_url' => null,
            ];

            // Store in session for development
            Session::put('linkedin_profile', $mockProfile);
            Session::put('person_id', $mockProfile['person_id']);

            // Also store in database for consistency
            $linkedinProfile = \App\Models\LinkedinProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'person_id' => $mockProfile['person_id'],
                    'name' => $mockProfile['name'],
                    'headline' => $mockProfile['headline'],
                    'access_token' => $mockProfile['access_token'],
                    'profile_picture_url' => $mockProfile['profile_picture_url'],
                ]
            );

            return response()->json([
                "message" => "Mock LinkedIn profile created successfully",
                "profile" => $mockProfile
            ]);
        }

        // Production mode: Real LinkedIn OAuth
        $state = bin2hex(random_bytes(16));
        Session::put('linkedin_oauth_state', $state);

        $authUrl = "https://www.linkedin.com/oauth/v2/authorization?" . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->linkedin_client_id,
            'redirect_uri' => $this->linkedin_redirect_uri,
            'state' => $state,
            'scope' => 'r_liteprofile r_emailaddress w_member_social'
        ]);

        return response()->json(["auth_url" => $authUrl]);
    }

    public function linkedinCallback(Request $request)
    {
        // Development mode: Redirect to dashboard
        if (config('app.env') === 'local' || config('app.debug') === true) {
            return redirect('http://localhost:8000/linkedin-ai');
        }

        $code = $request->get('code');
        $state = $request->get('state');

        // Verify state parameter
        if (!$state || $state !== Session::get('linkedin_oauth_state')) {
            return response()->json(["error" => "Invalid state parameter"], 400);
        }

        // Exchange code for access token
        $tokenResponse = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->linkedin_redirect_uri,
            'client_id' => $this->linkedin_client_id,
            'client_secret' => $this->linkedin_client_secret,
        ]);

        if (!$tokenResponse->successful()) {
            return response()->json(["error" => "Failed to get access token"], 400);
        }

        $tokenData = $tokenResponse->json();
        $accessToken = $tokenData['access_token'];

        // Get user profile
        $profileResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'X-Restli-Protocol-Version' => '2.0.0'
        ])->get('https://api.linkedin.com/v2/people/~:(id,firstName,lastName,headline,profilePicture(displayImage~:playableStreams))');

        if (!$profileResponse->successful()) {
            return response()->json(["error" => "Failed to get profile"], 400);
        }

        $profileData = $profileResponse->json();
        
        $user = Auth::user();
        if (!$user) {
            return response()->json(["error" => "Not logged in"], 401);
        }

        // Store LinkedIn profile
        $linkedinProfile = \App\Models\LinkedinProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'person_id' => $profileData['id'],
                'name' => $profileData['firstName']['localized']['en_US'] . ' ' . $profileData['lastName']['localized']['en_US'],
                'headline' => $profileData['headline']['localized']['en_US'] ?? 'Member',
                'access_token' => $accessToken,
                'profile_picture_url' => $profileData['profilePicture']['displayImage~']['elements'][0]['identifiers'][0]['identifier'] ?? null,
            ]
        );

        return redirect('http://localhost:8000/linkedin-ai');
    }

    public function profile(Request $request)
    {
        // Initialize output buffering to capture any unintended output
        ob_start();
        
        // Log the request for debugging
        \Log::info('Profile endpoint called with headers: ' . json_encode($request->headers->all()));
        
        try {
            // Check for temporary token in Authorization header FIRST
            $authHeader = $request->header('Authorization');
            \Log::info('Authorization header: ' . $authHeader);
            
            if ($authHeader && str_starts_with($authHeader, 'Bearer temp_token_')) {
                $token = str_replace('Bearer temp_token_', '', $authHeader);
                $user = \App\Models\User::find($token);
                if (!$user) {
                    ob_end_clean();
                    return response()->json(["error" => "Invalid token"], 401);
                }

                // Check for LinkedIn profile in database
                $linkedinProfile = \App\Models\LinkedinProfile::where('user_id', $user->id)->first();
                if ($linkedinProfile) {
                    ob_end_clean();
                    return response()->json([
                        "name" => $linkedinProfile->name,
                        "person_id" => $linkedinProfile->person_id,
                        "headline" => $linkedinProfile->headline,
                        "profilePic" => $linkedinProfile->profile_picture_url,
                        "access_token" => $linkedinProfile->access_token,
                    ]);
                }

                // Return basic user info if no LinkedIn profile exists
                ob_end_clean();
                return response()->json([
                    "name" => $user->name,
                    "person_id" => null,
                    "headline" => "Member",
                    "profilePic" => null,
                    "access_token" => null,
                ]);
            }

            $user = Auth::user();                // <-- use Auth
            if (!$user) {
                ob_end_clean();
                return response()->json(["error" => "Not logged in"], 401);
            }

            // Check for mock LinkedIn data in development mode
            if (config('app.env') === 'local' || config('app.debug') === true) {
                $sessionProfile = Session::get('linkedin_profile');
                if ($sessionProfile) {
                    ob_end_clean();
                    return response()->json([
                        "name"         => $user->name,
                        "person_id"    => Session::get('person_id'),
                        "headline"     => "Development User",
                        "profilePic"   => null,
                        "access_token" => "mock_token",
                    ]);
                }
            }

            $linkedinProfile = \App\Models\LinkedinProfile::where('user_id', $user->id)->first();
            if (!$linkedinProfile) {
                ob_end_clean();
                // Return basic user info when no LinkedIn profile exists
                return response()->json([
                    "name" => $user->name,
                    "person_id" => null,
                    "headline" => "Member",
                    "profilePic" => null,
                    "access_token" => null,
                ]);
            }

            ob_end_clean();
            return response()->json([
                "name" => $linkedinProfile->name,
                "person_id" => $linkedinProfile->person_id,
                "headline" => $linkedinProfile->headline,
                "profilePic" => $linkedinProfile->profile_picture_url,
                "access_token" => $linkedinProfile->access_token,
            ]);
        } catch (\Exception $e) {
            ob_end_clean();
            // If linkedin_profiles table doesn't exist, return basic user info
            return response()->json([
                "name" => $user->name ?? "User",
                "person_id" => null,
                "headline" => "Member",
                "profilePic" => null,
                "access_token" => null,
            ]);
        }
    }

    public function checkLinkedInConnection(Request $request)
    {
        // Check for temporary token in Authorization header
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer temp_token_')) {
            $token = str_replace('Bearer temp_token_', '', $authHeader);
            $user = \App\Models\User::find($token);
            if (!$user) {
                return response()->json(["error" => "Invalid token"], 401);
            }

            // Check for LinkedIn profile in database
            $linkedinProfile = \App\Models\LinkedinProfile::where('user_id', $user->id)->first();
            if ($linkedinProfile) {
                return response()->json([
                    "connected" => true,
                    "profile" => [
                        "name" => $linkedinProfile->name,
                        "person_id" => $linkedinProfile->person_id,
                        "headline" => $linkedinProfile->headline,
                        "profile_picture_url" => $linkedinProfile->profile_picture_url,
                    ]
                ]);
            }

            return response()->json(["connected" => false]);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(["error" => "Not logged in"], 401);
        }

        // Check for mock LinkedIn data in development mode
        if (config('app.env') === 'local' || config('app.debug') === true) {
            $sessionProfile = Session::get('linkedin_profile');
            if ($sessionProfile) {
                return response()->json([
                    "connected" => true,
                    "profile" => $sessionProfile
                ]);
            }
        }

        $linkedinProfile = \App\Models\LinkedinProfile::where('user_id', $user->id)->first();
        
        if ($linkedinProfile) {
            return response()->json([
                "connected" => true,
                "profile" => [
                    "name" => $linkedinProfile->name,
                    "person_id" => $linkedinProfile->person_id,
                    "headline" => $linkedinProfile->headline,
                    "profile_picture_url" => $linkedinProfile->profile_picture_url,
                ]
            ]);
        }

        return response()->json(["connected" => false]);
    }

    public function disconnectLinkedIn(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(["error" => "Not logged in"], 401);
        }

        // Clear session data in development mode
        if (config('app.env') === 'local' || config('app.debug') === true) {
            Session::forget('linkedin_profile');
            Session::forget('person_id');
        }

        // Remove from database
        \App\Models\LinkedinProfile::where('user_id', $user->id)->delete();

        return response()->json(["message" => "LinkedIn disconnected successfully"]);
    }
}