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
