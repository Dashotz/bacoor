<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckOtpVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        // Check if OTP has been verified
        if (!session('otp_verified')) {
            Auth::logout();
            return redirect()->route('login.form')->withErrors(['email' => 'Please complete OTP verification.']);
        }

        // Check session timeout (5 minutes of inactivity)
        $lastActivity = session('last_activity');
        if ($lastActivity) {
            // Convert stored timestamp back to Carbon instance if it's a timestamp
            if (is_numeric($lastActivity)) {
                $lastActivity = Carbon::createFromTimestamp($lastActivity);
            }
            
            if (Carbon::now()->diffInMinutes($lastActivity) >= 5) {
                Auth::logout();
                session()->flush();
                return redirect()->route('login.form')->withErrors(['email' => 'Session expired due to inactivity. Please log in again.']);
            }
        }

        // Update last activity timestamp
        session(['last_activity' => Carbon::now()]);

        return $next($request);
    }
}
