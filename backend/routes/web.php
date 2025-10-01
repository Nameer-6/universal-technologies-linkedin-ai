<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkedinAuthcontroller;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReportIssueController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ImageGenController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserCreditController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('app');
});

Route::get('/linkedin-ai', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/login', function () {
    return view('app'); // Show login form
});

Route::get('/generate-image', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any/', '.*');


Route::get('/welcome', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/home-new', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/login', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/billing-details', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/sign-up', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');


Route::get('/register', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');
Route::get('/update-password', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/carousel-maker', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/edit-scheduled-post/:id', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');
Route::get('/metrics', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/reset-password', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/forgot-password', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::get('/create-promo', function () {
    return view('app'); // Ensure 'app.blade.php' is configured to load your React app
})->where('any', '.*');

Route::post('/api/update-password', [AuthController::class, 'updatePassword']);
Route::post('/api/send-email', [MailController::class, 'sendEmail']);

Route::post('/api/contact-request', [ContactController::class, 'store']);


Route::post('/api/admin/login', [AdminController::class, 'login']);
Route::get('/api/users/count', [AdminController::class, 'countUsers']);
Route::get('/api/users/paid/count', [AdminController::class, 'countPaidUsers']);
Route::get('/api/users/free-trial/count', [AdminController::class, 'countFreeTrialUsers']);
Route::get('/api/total-post', [AdminController::class, 'countTotalPosts']);
Route::get('/api/posting-activity', [AdminController::class, 'postingActivity']);
Route::get('/api/post-distribution', [AdminController::class, 'postDistribution']);
Route::get('/api/latest-posts', [AdminController::class, 'latestPosts']);
Route::get('/api/users/growth', [AdminController::class, 'growthByDate']);
Route::get('/api/users/recent', [AdminController::class, 'recentUsers']);
Route::get('/api/linkedin-post-stats', [LinkedinAuthcontroller::class, 'getLinkedinPostStats']);
Route::get('/api/multi-person-engagement', [LinkedinAuthcontroller::class, 'getMultiPersonEngagement']);
Route::post('/api/login', [AuthController::class, 'login'])->middleware('web');

// Temporary signup endpoint to recreate test users
Route::post('/api/signup-free', function (\Illuminate\Http\Request $request) {
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'credits' => 50 // Give some free credits
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Account created successfully! You can now log in.',
            'user' => $user->only(['id', 'name', 'email', 'credits'])
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::post('/api/password/email', [PasswordResetLinkController::class, 'sendResetLinkEmail']);
Route::post('/api/password/reset', [NewPasswordController::class, 'reset']);
Route::middleware(['web'])->group(function () {
    Route::get('/api/scheduled-posts/{id}', [LinkedInController::class, 'getScheduledPost']);
    Route::post('/api/scheduled-posts/update', [LinkedInController::class, 'updateScheduledPost']);
    Route::post('/api/scheduled-posts/delete', [LinkedInController::class, 'deleteScheduledPost']);
    Route::get('/api/sample-post', [LinkedInController::class, 'postSampleContent']);


    Route::get('/linkedin-callback', [LinkedinAuthcontroller::class, 'linkedinCallback']);
    Route::get('/api/logout', [LinkedinAuthcontroller::class, 'logout']);

    Route::get('/api/metrics',               [LinkedInController::class, 'getAllPostMetrics']);

    Route::post('/api/generate-post', [LinkedInController::class, 'generatePostApi']);
    Route::post('/api/cancel-subscription', [AuthController::class, 'cancelSubscription']);
    Route::get('/api/user/subscription', [AuthController::class, 'currentUserSubscription']);
    Route::get('/api/user-scheduled-posts', [LinkedInController::class, 'getUserScheduledPosts']);
    Route::get('/api/news-categories', [LinkedInController::class, 'getNewsCategories']);
    Route::get('/api/news-by-category', [LinkedInController::class, 'getNewsByCategory']);
    Route::post('/api/process-payment', [AuthController::class, 'processPayment']);
    Route::post('/api/linkedin-post', [LinkedInController::class, 'linkedinPostApi']);
    Route::post('/api/generate-news', [CarouselController::class, 'fetchTrendingTopics']);
    Route::post('/api/generate-ppt', [CarouselController::class, 'generatePPTSlides']);

    Route::get('/api/promo/create', [PromoController::class, 'showCreateForm']);
    // Handle the JSON POST to actually create the coupon in Stripe
    Route::post('/api/promo/create', [PromoController::class, 'create']);
    Route::get('/api/promo/list', [PromoController::class, 'list']); // New for listing all promos
    Route::post('/api/report-issue', [ReportIssueController::class, 'store'])->name('api.report-issue');
    Route::post('/api/generate-image', [ImageGenController::class, 'generate']);
    Route::post('/api/generate-image-idea', [LinkedInController::class, 'generateImageIdea']);
    Route::get('/api/user/credits', [UserCreditController::class, 'getCredits']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/api/whoami', [AuthApiController::class, 'whoami']);
    Route::get('/api/profile', [LinkedinAuthcontroller::class, 'profile']);
    Route::get('/api/linkedin-login', [LinkedinAuthcontroller::class, 'linkedinLogin']);
    Route::get('/api/check-linkedin', [LinkedinAuthcontroller::class, 'checkLinkedInConnection']);
    Route::post('/api/generate-options', [NewsController::class, 'generateOptions']);
    Route::post('/api/generate-content', [NewsController::class, 'generateContent']); // New GPT content generation
});

