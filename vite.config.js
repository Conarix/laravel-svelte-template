import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import { svelte } from '@sveltejs/vite-plugin-svelte';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
        tailwindcss(),
        svelte(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': path.resolve(__dirname, './vendor/tightenco/ziggy')
        }
    },
    server: {
        origin: 'http://localhost:5173',
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },
    css: {
        preprocessorOptions: {
            scss: {

            }
        }
    }
});
