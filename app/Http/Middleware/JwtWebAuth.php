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
            // For web routes, try to get JWT token from URL query parameter first
            $token = $request->query('token');
            if ($token) {
                // Set the token in the request headers so JWTAuth can parse it
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }
            
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return redirect('/');
            }
        } catch (TokenExpiredException $e) {
            return redirect('/');
        } catch (TokenInvalidException $e) {
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/');
        }

        return $next($request);
    }
}
