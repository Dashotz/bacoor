<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtWebAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // For web routes, try to get JWT token from session first, then URL query parameter
            $token = session('jwt_token') ?? $request->query('token');
            
            \Log::info('JWT Web Auth Check', [
                'url' => $request->url(),
                'has_session_token' => !empty(session('jwt_token')),
                'has_query_token' => !empty($request->query('token')),
                'token_length' => $token ? strlen($token) : 0
            ]);
            
            if (!$token) {
                \Log::info('No JWT token found, redirecting to home');
                return redirect('/');
            }
            
            // Set the token in the request headers so JWTAuth can parse it
            $request->headers->set('Authorization', 'Bearer ' . $token);
            
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                \Log::info('JWT authentication failed - no user found');
                return redirect('/');
            }
            
            \Log::info('JWT authentication successful', ['user_id' => $user->id]);
        } catch (TokenExpiredException $e) {
            \Log::info('JWT token expired');
            return redirect('/');
        } catch (TokenInvalidException $e) {
            \Log::info('JWT token invalid');
            return redirect('/');
        } catch (\Exception $e) {
            \Log::error('JWT Web Auth Exception: ' . $e->getMessage());
            return redirect('/');
        }

        return $next($request);
    }
}
