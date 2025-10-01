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

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
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
