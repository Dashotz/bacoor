<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asset Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for external assets like CSS, JS,
    | and third-party services.
    |
    */

    'external' => [
        'google_fonts' => [
            'family' => 'Inter:wght@300;400;500;600;700',
            'display' => 'swap',
        ],
        
        'recaptcha' => [
            'script_url' => 'https://www.google.com/recaptcha/api.js',
            'async' => true,
            'defer' => true,
        ],
    ],

    'pages' => [
        'login' => [
            'styles' => ['resources/css/home.css'],
            'scripts' => ['resources/js/home.js', 'resources/js/jwt-auth.js', 'resources/js/login.js'],
            'external_scripts' => ['recaptcha'],
        ],
        
        'register' => [
            'styles' => ['resources/css/register.css'],
            'scripts' => ['resources/js/jwt-auth.js', 'resources/js/register.js'],
        ],
        
        'dashboard' => [
            'styles' => ['resources/css/dashboard.css'],
            'scripts' => ['resources/js/dashboard.js', 'resources/js/jwt-auth.js'],
        ],
        
        'landing' => [
            'styles' => ['resources/css/landing.css'],
            'scripts' => ['resources/js/landing.js'],
        ],
        
        'otp' => [
            'styles' => ['resources/css/otp.css'],
            'scripts' => ['resources/js/otp.js'],
        ],
        
        'application-status' => [
            'styles' => ['resources/css/application-status.css'],
            'scripts' => ['resources/js/application-status.js'],
        ],
    ],
];
