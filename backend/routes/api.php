<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkedInController;
use App\Http\Controllers\AuthController;

// Health check endpoint for Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Universal Technologies LinkedIn AI Agent'
    ]);
});

// Temporary endpoint to create test user
Route::post('/create-test-user', function () {
    try {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'credits' => 100
        ]);
        
        return response()->json([
            'success' => true,
            'user' => $user->only(['id', 'name', 'email', 'credits'])
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

// Temporary signup endpoint without payment
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
Route::get('/checkout/success', [AuthController::class, 'checkoutSuccess'])
    ->name('checkout.success');

// 3) (Optional) Handle canceled payment
Route::get('/checkout/cancel', [AuthController::class, 'checkoutCancel'])
    ->name('checkout.cancel');

// Route::middleware(['web'])->group(function () {
//     Route::post('/cancel-subscription', [AuthController::class, 'cancelSubscription']);
//     Route::get('/user/subscription', [AuthController::class, 'currentUserSubscription']);
// });
