<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(App\Http\Middleware\SecurityHeadersMiddleware::class);
        // $middleware->append(App\Http\Middleware\SessionTimeout::class); // Disabled - designed for session auth, not JWT
        
        // Configure auth middleware to redirect to login
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'jwt.web' => \App\Http\Middleware\JwtWebAuth::class,
            'otp.verified' => \App\Http\Middleware\CheckOtpVerification::class,
        ]);
        
        // Exclude API routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'otp/*',
            'session/*',
            'forgot-password',
            'reset-password'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
