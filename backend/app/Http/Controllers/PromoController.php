<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use App\Models\PromoCode;

class PromoController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    // (Optional) Blade form
    public function showCreateForm()
    {
        return view('promo.create');
    }

    // Create a new promo code (POST /api/promo/create)
    public function create(Request $request)
    {
        $validated = $request->validate([
            'code'            => 'required|string|max:50',
            'type'            => 'required|in:percentage,free_months',
            'percent_off'     => 'nullable|integer|min:1|max:100',
            'duration_months' => 'nullable|integer|min:1|max:24',
        ]);

        $couponId = strtoupper(trim($validated['code']));

        try {
            if ($validated['type'] === 'percentage') {
                if (is_null($validated['percent_off'])) {
                    return response()->json([
                        'error' => 'percent_off is required when type=percentage'
                    ], 422);
                }

                $stripeCoupon = $this->stripe->coupons->create([
                    'id'          => $couponId,
                    'percent_off' => $validated['percent_off'],
                    'duration'    => 'once',
                ]);

                PromoCode::create([
                    'code'             => $couponId,
                    'type'             => 'percentage',
                    'percent_off'      => $validated['percent_off'],
                    'duration_months'  => null,
                    'stripe_coupon_id' => $stripeCoupon->id,
                ]);
            } else {
                if (is_null($validated['duration_months'])) {
                    return response()->json([
                        'error' => 'duration_months is required when type=free_months'
                    ], 422);
                }

                $stripeCoupon = $this->stripe->coupons->create([
                    'id'                 => $couponId,
                    'percent_off'        => $validated['percent_off'],
                    'duration'           => 'repeating',
                    'duration_in_months' => $validated['duration_months'],
                ]);

                PromoCode::create([
                    'code'             => $couponId,
                    'type'             => 'free_months',
                    'percent_off'      => $validated['percent_off'] ?? null,
                    'duration_months'  => $validated['duration_months'],
                    'stripe_coupon_id' => $stripeCoupon->id,
                ]);
            }

            Log::info("Created Stripe coupon and saved to DB: {$couponId}", [
                'coupon' => $stripeCoupon,
            ]);

            return response()->json([
                'message' => 'Coupon created successfully.',
                'coupon'  => $stripeCoupon,
            ], 201);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::warning("Stripe coupon creation failed: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 400);

        } catch (\Exception $e) {
            Log::error("Unexpected error creating coupon: " . $e->getMessage());
            return response()->json([
                'error' => 'Server error. Please try again.'
            ], 500);
        }
    }

    // List all promo codes, newest first (GET /api/promo/list)
    public function list()
    {
        $promos = PromoCode::orderBy('id', 'desc')->get();
        return response()->json(['promos' => $promos]);
    }
}
