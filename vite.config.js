import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/admin.css'],
            publicDirectory: 'resources/assets',
            buildDirectory: 'css',
            refresh: true,
        }),
    ],
});
