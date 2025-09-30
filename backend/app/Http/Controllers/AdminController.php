<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ScheduledPost;
class AdminController extends Controller
{
    // Handle admin login
    public function login(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Manually authenticate the admin (without using guards)
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // If successful, generate a token for the admin
            $token = $admin->createToken('AdminToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'data' => [
                    'admin' => $admin,
                    'token' => $token,
                ],
            ], 200);
        }

        // If authentication fails, return an error
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }

    // Admin logout
    public function logout(Request $request)
    {
        // Get the authenticated admin using the Admin model
        $admin = Auth::guard('admin')->user();

        // Revoke all tokens for the authenticated admin
        $admin->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ], 200);
    }
    public function countUsers()
    {
        // Count the number of users in the database
        $userCount = User::count();

        // Log the number of users
        Log::info("Total users count: {$userCount}");

        // Return the user count as a JSON response
        return response()->json([
            'user_count' => $userCount,
        ], 200);
    }
    public function countPaidUsers()
    {
        // Count users with paid subscriptions directly using DB facade
        $paidUserCount = DB::table('subscriptions')
            ->where('stripe_status', 'active')
            ->count();

        // Log the count of paid users
        Log::info("Total Paid Users Count: {$paidUserCount}");

        // Return the paid user count as a JSON response
        return response()->json([
            'paid_user_count' => $paidUserCount,
        ], 200);
    }

    // Count Free Trial Users
    public function countFreeTrialUsers()
    {
        // Count users with free trial subscriptions directly using DB facade
        $freeTrialUserCount = DB::table('subscriptions')
            ->where('stripe_status', 'trialing')
            ->count();

        // Log the count of free trial users
        Log::info("Total Free Trial Users Count: {$freeTrialUserCount}");

        // Return the free trial user count as a JSON response
        return response()->json([
            'free_trial_user_count' => $freeTrialUserCount,
        ], 200);
    }
    public function growthByDate()
    {
        $rows = User::join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->selectRaw("
                DATE(subscriptions.created_at) AS date,
                SUM(CASE 
                      WHEN subscriptions.stripe_status IN ('trialing','active') 
                      THEN 1 ELSE 0 
                    END) AS total_count,
                SUM(CASE 
                      WHEN subscriptions.stripe_status = 'trialing' 
                      THEN 1 ELSE 0 
                    END) AS trial_count,
                SUM(CASE 
                      WHEN subscriptions.stripe_status = 'active' 
                      THEN 1 ELSE 0 
                    END) AS paid_count
            ")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    
        $data = $rows->map(function ($row) {
            return [
                'name'  => Carbon::parse($row->date)->format('d/m/y'),
                'total' => (int) $row->total_count,
                'trial' => (int) $row->trial_count,
                'paid'  => (int) $row->paid_count,
            ];
        });
    
        return response()->json([
            'user_growth' => $data,
        ]);
    }
    public function recentUsers()
    {
        $rows = User::join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
            ->selectRaw("
                subscriptions.id,
                users.name,
                users.email,
                subscriptions.stripe_status AS plan,
                subscriptions.created_at
            ")
            ->orderBy('subscriptions.created_at', 'desc')
            ->limit(5)
            ->get();
    
        $data = $rows->map(function ($row) {
            return [
                'id'     => $row->id,
                'name'   => $row->name,
                'email'  => $row->email,
                'plan'   => $row->plan,
                'joined' => Carbon::parse($row->created_at)->format('Y-m-d'),
            ];
        });
    
        return response()->json([
            'recent_users' => $data,
        ]);
    }
    public function postingActivity()
    {
        // Group by date (YYYY-MM-DD), count posts
        $rows = ScheduledPost::selectRaw("DATE(scheduled_datetime) as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format for frontend: date => dd/mm/yy
        $activity = $rows->map(function ($row) {
            return [
                'date'  => Carbon::parse($row->date)->format('d/m/y'),
                'posts' => (int) $row->count,
            ];
        });

        return response()->json([
            'posting_activity' => $activity,
        ]);
    }
    public function countTotalPosts()
    {
        $total = ScheduledPost::count();
        return response()->json([
            'total_posts' => $total
        ]);
    }
    public function postDistribution()
    {
        $rows = ScheduledPost::selectRaw("
            CASE
                WHEN media_type LIKE 'image/%' THEN 'Image'
                WHEN media_type LIKE 'video/%' THEN 'Video'
                ELSE 'Article'
            END AS type,
            COUNT(*) AS count
        ")
            ->groupBy('type')
            ->get();

        // ensure all three types are present
        $dist = ['Article' => 0, 'Image' => 0, 'Video' => 0];
        foreach ($rows as $row) {
            $dist[$row->type] = (int) $row->count;
        }

        return response()->json([
            'distribution' => $dist
        ]);
    }
    public function latestPosts()
    {
        $posts = ScheduledPost::orderBy('scheduled_datetime', 'desc')
            ->take(5)
            ->get(['id', 'post_text', 'scheduled_datetime']);

        // Map to API shape
        $data = $posts->map(function ($p) {
            return [
                'id'    => $p->id,
                'title' => mb_strimwidth(strip_tags($p->post_text), 0, 50, '…'),
                'date'  => Carbon::parse($p->scheduled_datetime)->format('d/m/Y'),
            ];
        });

        return response()->json([
            'latest_posts' => $data,
        ]);
    }

}
