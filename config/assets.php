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
            'styles' => ['resources/css/auth/home.css'],
            'scripts' => ['resources/js/auth/home.js', 'resources/js/core/jwt-auth.js', 'resources/js/auth/login.js'],
            'external_scripts' => ['recaptcha'],
        ],
        
        'register' => [
            'styles' => ['resources/css/auth/register.css'],
            'scripts' => ['resources/js/core/jwt-auth.js', 'resources/js/auth/register.js'],
        ],
        
        'dashboard' => [
            'styles' => ['resources/css/pages/dashboard.css'],
            'scripts' => ['resources/js/pages/dashboard.js', 'resources/js/core/jwt-auth.js'],
        ],
        
        'landing' => [
            'styles' => ['resources/css/pages/landing.css'],
            'scripts' => ['resources/js/pages/landing.js'],
        ],
        
        'otp' => [
            'styles' => ['resources/css/auth/otp.css'],
            'scripts' => ['resources/js/auth/otp.js'],
        ],
        
    ],
];
