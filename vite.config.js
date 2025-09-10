import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/home.css',
                'resources/js/home.js',
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
                'resources/css/otp.css',
                'resources/js/otp.js',
                'resources/js/jwt-auth.js',
                'resources/css/register.css',
                'resources/js/register.js',
                'resources/js/login.js',
                'resources/css/landing.css',
                'resources/js/landing.js',
                'resources/css/transfer-of-ownership.css',
                'resources/js/transfer-of-ownership.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
