import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/core/app.js',
                'resources/js/core/bootstrap.js',
                'resources/js/core/jwt-auth.js',
                // Auth assets
                'resources/css/auth/home.css',
                'resources/js/auth/home.js',
                'resources/css/auth/register.css',
                'resources/js/auth/register.js',
                'resources/js/auth/login.js',
                'resources/css/auth/otp.css',
                'resources/js/auth/otp.js',
                // Page assets
                'resources/css/pages/dashboard.css',
                'resources/js/pages/dashboard.js',
                'resources/css/pages/landing.css',
                'resources/js/pages/landing.js',
                'resources/css/pages/transfer-of-ownership.css',
                'resources/js/pages/transfer-of-ownership.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
