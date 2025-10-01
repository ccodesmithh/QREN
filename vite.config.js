import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
VITE_PUSHER_APP_KEY=b9e270c5bfc8cbef3676
VITE_PUSHER_APP_CLUSTER=ap1