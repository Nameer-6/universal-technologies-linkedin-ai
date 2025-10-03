<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LinkedinAuthcontroller;

// Health check endpoint for Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Universal Technologies LinkedIn AI Agent'
    ]);
});

// Test database connection
Route::get('/test-db', function () {
    try {
        $userCount = \App\Models\User::count();
        return response()->json([
            'status' => 'ok',
            'user_count' => $userCount,
            'message' => 'Database connection working'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ], 500);
    }
});

// Temporary signup endpoint to recreate test users
Route::post('/signup-free', function (\Illuminate\Http\Request $request) {
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

Route::post('/signup', [AuthController::class, 'register']);

// Simple login endpoint for testing
Route::post('/login', function (\Illuminate\Http\Request $request) {
    try {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $user = \App\Models\User::where('email', $validated['email'])->first();
        
        if (!$user || !\Illuminate\Support\Facades\Hash::check($validated['password'], $user->password)) {
            return response()->json(['error' => 'Invalid credentials.'], 422);
        }

        return response()->json([
            'ok' => true,
            'user' => $user->only(['id', 'name', 'email']),
            'token' => 'temp_token_' . $user->id,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
});
Route::get('/checkout/success', [AuthController::class, 'checkoutSuccess'])
    ->name('checkout.success');

// 3) (Optional) Handle canceled payment
Route::get('/checkout/cancel', [AuthController::class, 'checkoutCancel'])
    ->name('checkout.cancel');

// News API endpoints
Route::post('/generate-options', [NewsController::class, 'generateOptions']);
Route::post('/generate-content', [NewsController::class, 'generateContent']);

// Profile endpoint in api.php (without auth:sanctum middleware)
Route::get('/profile', [LinkedinAuthcontroller::class, 'profile']);

// Route::middleware(['web'])->group(function () {
//     Route::post('/cancel-subscription', [AuthController::class, 'cancelSubscription']);
//     Route::get('/user/subscription', [AuthController::class, 'currentUserSubscription']);
// });
