import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/message.css',
                'resources/css/messageList.css',
                'resources/css/list.css',
                'resources/css/style.css',
                'resources/css/welcome.css',
                'resources/js/app.js',
                'resources/js/script.js',
                'resources/js/message.js'
            ],
            refresh: true,
        }),
    ],
});
