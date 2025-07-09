import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/formateur.css',
                'resources/css/admin.css',
                'resources/js/app.js',
                'resources/js/components/formateur.js',
                'resources/js/components/admin.js'
            ],
            refresh: true,
        }),
    ],
});
