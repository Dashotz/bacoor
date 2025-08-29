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
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
