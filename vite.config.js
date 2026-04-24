import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/style.css',
                'resources/css/admin-eventos.css',
                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/register.js',
                'resources/js/index.js',
                'resources/js/admin-eventos.js',
                'resources/js/perfil.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
