<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next)
    {
        // 1) Pull the current user
        $userId = $request->session()->get('user_id');
        if (! $userId || ! $user = User::find($userId)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 2) Use Cashier to get the “default” subscription object
        $cashierSub = $user->subscription('default');

        // 3) If they’ve never subscribed at all → redirect out
        if (! $cashierSub) {
            return redirect()->away('https://universaltechnologies.com/');
        }

        // 4) If you need your local record (to check trial_ends_at or ends_at), look it up by stripe_id:
        $localSub = $user->subscriptions()
                         ->where('stripe_id', $cashierSub->stripe_id)
                         ->first();

        // 5) Now decide if we deny:
        $deny =
            // not active AND not on trial
            (! $cashierSub->active() && ! $cashierSub->onTrial())
            // OR trial expired
            || (
                $cashierSub->onTrial() 
                && $localSub 
                && $localSub->trial_ends_at 
                && Carbon::now()->greaterThan($localSub->trial_ends_at)
            )
            // OR cancelled/ended
            || (
                $localSub 
                && $localSub->ends_at 
                && Carbon::now()->greaterThan($localSub->ends_at)
            );

        if ($deny) {
            return response()->json([
                'error' => 'Subscription inactive or expired. Please renew to access this resource.'
            ], 402);
        }

        return $next($request);
    }
}
