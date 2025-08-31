<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Otp;

class CheckOtpVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip OTP verification for logout routes
        if ($request->is('logout')) {
            return $next($request);
        }
        
        // Check if user has valid JWT token
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return redirect('/');
            }
        } catch (\Exception $e) {
            return redirect('/');
        }

        // Check if OTP has been verified by checking the database
        $latestOtp = Otp::where('user_id', $user->id)
                        ->where('used', true)
                        ->latest()
                        ->first();

        if (!$latestOtp) {
            // Clear JWT token and redirect to OTP verification
            return redirect('/otp');
        }

        return $next($request);
    }
}
