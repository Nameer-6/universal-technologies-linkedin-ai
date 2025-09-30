<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class AuthApiController extends Controller
{
    /**
     * Get the authenticated user's status
     */
    public function whoami(Request $request)
    {
        // Initialize output buffering to capture any unintended output
        ob_start();
        
        try {
            $user = Auth::user();
            
            $response = [
                'auth_id' => Auth::id(),
                'auth_check' => Auth::check(),
                'auth_user' => $user ? $user->email : null,
            ];
            
            // Clean any buffered output
            ob_end_clean();
            
            return response()->json($response)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                
        } catch (\Exception $e) {
            // Clean any buffered output
            ob_end_clean();
            
            return response()->json([
                'auth_id' => null,
                'auth_check' => false,
                'auth_user' => null,
                'error' => 'Authentication check failed'
            ])->header('Content-Type', 'application/json');
        }
    }
}