<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            $timeout = config('session.lifetime', 3) * 60; // Convert minutes to seconds
            $currentTime = Carbon::now();
            
            // If no last_activity is set, set it now (first request after login)
            if (!$lastActivity) {
                Session::put('last_activity', $currentTime);
                $lastActivity = $currentTime;
                Log::info('Session timeout: Initial last_activity set', [
                    'user_id' => Auth::id(),
                    'timestamp' => $currentTime->toDateTimeString()
                ]);
            } else {
                // Convert stored timestamp back to Carbon instance if it's a timestamp
                if (is_numeric($lastActivity)) {
                    $lastActivity = Carbon::createFromTimestamp($lastActivity);
                }
            }
            
            $timeSinceLastActivity = $currentTime->diffInSeconds($lastActivity);
            
            Log::info('Session timeout: Checking activity', [
                'user_id' => Auth::id(),
                'last_activity' => $lastActivity->toDateTimeString(),
                'current_time' => $currentTime->toDateTimeString(),
                'time_since_last' => $timeSinceLastActivity,
                'timeout' => $timeout,
                'expired' => $timeSinceLastActivity > $timeout
            ]);
            
            if ($timeSinceLastActivity > $timeout) {
                // Session has expired, log out the user
                Log::info('Session timeout: Session expired, logging out user', [
                    'user_id' => Auth::id(),
                    'last_activity' => $lastActivity->toDateTimeString(),
                    'timeout' => $timeout
                ]);
                
                Auth::logout();
                Session::flush();
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired'], 401);
                }
                
                return redirect()->route('login.form')->with('message', 'Your session has expired due to inactivity. Please log in again.');
            }
            
            // Update last activity timestamp
            Session::put('last_activity', $currentTime);
        }
        
        return $next($request);
    }
}
