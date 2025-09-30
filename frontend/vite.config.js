import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/index.jsx', // Entry for your React app
                'resources/css/app.css', // CSS entry point
            ],
            refresh: true,
            valetTls: false,
            publicDirectory: '../backend/public', // Point to backend's public directory
        }),
    ],
    server: {
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                secure: false,
            },
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'), // Alias for cleaner imports
        },
    },
    build: {
        outDir: '../backend/public/build', // Output directory (build to parent's backend/public)
        manifest: true,         // Generate manifest.json
        rollupOptions: {
            input: {
                main: 'resources/js/index.jsx',
                styles: 'resources/css/app.css', // Explicitly define the CSS input
            },
        },
    },
});
