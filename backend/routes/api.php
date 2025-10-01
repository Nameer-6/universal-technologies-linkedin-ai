<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;

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

// Route::middleware(['web'])->group(function () {
//     Route::post('/cancel-subscription', [AuthController::class, 'cancelSubscription']);
//     Route::get('/user/subscription', [AuthController::class, 'currentUserSubscription']);
// });
