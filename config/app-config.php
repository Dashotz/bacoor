<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains external configuration for the application
    | including tokens, API keys, and other sensitive data.
    |
    */

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    ],

    'jwt' => [
        'secret' => env('JWT_SECRET', ''),
        'ttl' => env('JWT_TTL', 60),
        'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
    ],

    'mail' => [
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@bacoor.gov.ph'),
        'from_name' => env('MAIL_FROM_NAME', 'Bacoor City Government'),
    ],

    'app' => [
        'name' => 'BACOOR CITY EGOV™',
        'url' => env('APP_URL', 'http://localhost'),
        'timezone' => env('APP_TIMEZONE', 'Asia/Manila'),
    ],

    'security' => [
        'otp_expiry' => 300, // 5 minutes in seconds
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutes in seconds
    ],
];
