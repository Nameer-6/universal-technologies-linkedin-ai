<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserCreditController extends Controller
{
    /**
     * Get the currently logged-in user's credits (using session user_id).
     */
    public function getCredits(Request $request)
    {
        $user = Auth::user();                // <-- use Auth

        if (!$user) {
            return response()->json(['error' => 'Not logged in.'], 401);
        }

        return response()->json([
            'credits' => $user->credits ?? 0,
        ]);
    }
}
