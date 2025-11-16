import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/chat.css',
                'resources/css/chat-list.css',
                'resources/css/list.css',
                'resources/css/style.css',
                'resources/css/welcome.css',
                'resources/js/app.js',
                'resources/js/script.js',
                'resources/js/chat.js'
            ],
            refresh: true,
        }),
    ],
});
