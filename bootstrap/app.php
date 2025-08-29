<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(App\Http\Middleware\SecurityHeadersMiddleware::class);
        $middleware->append(App\Http\Middleware\SessionTimeout::class);
        
        // Configure auth middleware to redirect to login
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'otp.verified' => \App\Http\Middleware\CheckOtpVerification::class,
        ]);
        
        // Set default redirect for unauthenticated users
        $middleware->redirectTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
