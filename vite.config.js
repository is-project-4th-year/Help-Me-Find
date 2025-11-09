import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/chat.css',
                'resources/css/grid.css',
                'resources/css/sign.css',
                'resources/css/style.css',
                'resources/css/welcome.css',
                'resources/js/app.js',
                'resources/js/chat.js',
                'resources/js/script.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
