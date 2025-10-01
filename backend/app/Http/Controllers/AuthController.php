<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $stripeSecret = config('services.stripe.secret');
        if ($stripeSecret && $stripeSecret !== 'sk_test_temp') {
            $this->stripe = new StripeClient($stripeSecret);
        }
    }

    public function register(Request $request)
    {
        Log::info("Register: validating input and creating Stripe Checkout session.");

        // Check if Stripe is configured
        if (!$this->stripe) {
            return response()->json(['error' => 'Payment system not configured. Please contact support.'], 500);
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',
            'recaptcha_token' => 'nullable|string', // Made optional for deployment
            'promo_code'      => 'nullable|string',
        ]);

        // 1) Verify reCAPTCHA (only if token provided)
        if (!empty($validated['recaptcha_token'])) {
            $recaptchaResponse = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret'),
                    'response' => $validated['recaptcha_token'],
                    'remoteip' => $request->ip(),
                ]
            );
            $recaptchaResult = $recaptchaResponse->json();

            if (empty($recaptchaResult['success'])) {
                Log::warning("reCAPTCHA failed.", $recaptchaResult);
                return response()->json(['error' => 'reCAPTCHA verification failed.'], 400);
            }
        }

        // 2) Cache registration data for 30 minutes
        $token = Str::uuid()->toString();
        cache()->put("registration:$token", $validated, now()->addMinutes(30));
        Log::info("Registration data cached.", [
            'email' => $validated['email'],
            'token' => $token,
        ]);

        // 3) Attempt to find promo_code in database (if provided)
        $stripeCouponId = null;
        if (!empty($validated['promo_code'])) {
            $enteredCode = strtoupper(trim($validated['promo_code']));
            $promo = PromoCode::find($enteredCode);

            if (! $promo) {
                // If promo code not found in DB, reject immediately
                return response()->json(['error' => 'Invalid promo code.'], 422);
            }

            // If found, use its stripe_coupon_id
            $stripeCouponId = $promo->stripe_coupon_id;
        }

        // 4) Prepare to create Stripe Checkout session
        $subscriptionPriceId = 'price_1REFDDDbtwcWf7lEy37FyAcU'; // ← replace with your real price ID

        try {
            // Build the base session array
            $sessionData = [
                'customer_email'       => $validated['email'],
                'payment_method_types' => ['card'],
                'mode'                 => 'subscription',
                'line_items'           => [
                    ['price' => $subscriptionPriceId, 'quantity' => 1],
                ],
                'subscription_data'    => [
                    // Always apply a 30-day trial
                    'trial_period_days' => 30,
                ],
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&token=' . $token,
                'cancel_url'  => route('checkout.cancel'),
            ];

            // 5) If a valid coupon exists, attach it under discounts
            if (!is_null($stripeCouponId)) {
                $sessionData['discounts'] = [
                    ['coupon' => $stripeCouponId],
                ];
            }

            // 6) Create the Stripe Checkout Session
            $session = $this->stripe->checkout->sessions->create($sessionData);

            Log::info("Stripe Checkout session created.", ['session_id' => $session->id]);
            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            Log::error("Stripe session creation failed: " . $e->getMessage());
            return response()->json(['error' => 'Stripe error. Please try again.'], 500);
        }
    }

    public function checkoutSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        $token     = $request->query('token');

        if (!$sessionId || !$token) {
            return redirect('/')->with('error', 'Missing payment session or registration token.');
        }

        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);
        } catch (\Exception $e) {
            Log::error("Stripe session retrieval failed: " . $e->getMessage());
            return redirect('/')->with('error', 'Invalid Stripe session.');
        }

        $data = cache()->pull("registration:$token");
        if (! $data) {
            Log::warning("Missing or expired registration data from cache.", ['token' => $token]);
            return redirect('/login')->with('error', 'Could not retrieve registration data.');
        }

        if (User::where('email', $data['email'])->exists()) {
            Log::warning("User already exists after payment.", ['email' => $data['email']]);
            return redirect('/login')->with('info', 'Account already exists. Please log in.');
        }

        // Create the user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'credits'  => 100, // <--- Set initial credits
        ]);

        // Save Stripe customer ID
        $user->stripe_id = $session->customer;
        $user->save();

        // If payment succeeded, store subscription record locally
        if ($session->payment_status === 'paid' && $session->subscription) {
            $subscription = $this->stripe->subscriptions->retrieve($session->subscription);

            $user->subscriptions()->create([
                'user_id'       => $user->id,
                'type'          => 'default',
                'stripe_id'     => $subscription->id,
                'stripe_status' => $subscription->status,
                'stripe_price'  => $subscription->items->data[0]->price->id,
                'quantity'      => 1,
                'trial_ends_at' => $subscription->trial_end
                    ? Carbon::createFromTimestamp($subscription->trial_end)
                    : null,
                'ends_at'       => $subscription->cancel_at
                    ? Carbon::createFromTimestamp($subscription->cancel_at)
                    : null,
            ]);
        }

        Log::info("User account created after successful payment.", ['user_id' => $user->id]);
        return redirect('/login')->with('success', 'Account created. Please log in.');
    }
    public function checkoutCancel()
    {
        Log::info("Stripe Checkout canceled.");
        return redirect('/login')->with('error', 'Payment canceled.');
    }

  public function login(Request $request)
{
    Log::info("Web Login attempt for email: {$request->email}");

    $validated = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
        'remember' => ['nullable', 'boolean'],
    ]);

    $remember = (bool) ($validated['remember'] ?? false);

    // Attempts login using the "web" guard and sets the remember_web_* cookie if $remember = true
    if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
        $request->session()->regenerate(); // protect against fixation
        
        $user = Auth::user();
        
        // Temporarily skip token creation to test basic login
        return response()->json([
            'ok'   => true,
            'user' => $user->only(['id', 'name', 'email']),
            'token' => 'temp_token_' . $user->id,
        ], 200);
    }

    // Wrong email or password
    return response()->json([
        'error' => 'Invalid credentials.',
    ], 422);
}


    public function billingDetails()
    {
        Log::info("Fetching all billing details.");
        return response()->json(
            User::select(
                'id',
                'name',
                'email',
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'trial_ends_at',
                'created_at',
                'updated_at'
            )->get()
        );
    }

    public function currentUserSubscription(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId || !($user = User::find($userId))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = $user->subscription('default');

        if (!$subscription) {
            return response()->json(['active' => false, 'message' => 'No active subscription.']);
        }

        $dbSubscription = $user->subscriptions()->where('stripe_id', $subscription->stripe_id)->first();

        if (!$dbSubscription) {
            return response()->json(['active' => false, 'message' => 'Subscription not found in database.']);
        }

        return response()->json([
            'id'            => $dbSubscription->id,
            'user_id'       => $dbSubscription->user_id,
            'type'          => $dbSubscription->name,
            'stripe_id'     => $dbSubscription->stripe_id,
            'stripe_status' => $dbSubscription->stripe_status,
            'stripe_price'  => $dbSubscription->stripe_price,
            'quantity'      => $dbSubscription->quantity,
            'trial_ends_at' => $dbSubscription->trial_ends_at,
            'ends_at'       => $dbSubscription->ends_at,
            'active'        => $subscription->active(),
            'canceled'      => $subscription->canceled(),
        ]);
    }

    public function cancelSubscription(Request $request)
    {
        $userId = $request->session()->get('user_id');
        if (! $userId || ! ($user = User::find($userId))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 1) Grab your local Subscription model instance:
        $localSub = $user->subscriptions()
            ->where('name', 'default')
            ->first();

        if (! $localSub) {
            return response()->json(['message' => 'No subscription found.'], 404);
        }

        try {
            // 2) Cancel immediately on Stripe
            $stripeSub = $this->stripe->subscriptions->cancel(
                $localSub->stripe_id,
                ['invoice_now' => true, 'prorate' => true]
            );

            // 3) Update your database record
            $localSub->stripe_status = $stripeSub->status;              // should be "canceled"
            $localSub->ends_at       = Carbon::createFromTimestamp(
                $stripeSub->canceled_at ?: time()
            );
            $localSub->save();

            Log::info("Subscription canceled on Stripe and DB updated.", [
                'user_id'       => $user->id,
                'stripe_status' => $stripeSub->status,
            ]);
        } catch (\Exception $e) {
            Log::warning("Stripe cancel error: " . $e->getMessage());
            return response()->json(['message' => 'Could not cancel subscription.'], 400);
        }

        return response()->json(['message' => 'Subscription canceled successfully.']);
    }
    public function health()
    {
        return response()->json(['status' => 'ok']);
    }
    public function updatePassword(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId || !($user = User::find($userId))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info("Password updated successfully.", ['user_id' => $user->id]);

        return response()->json(['message' => 'Password updated successfully']);
    }
}
