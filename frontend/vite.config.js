import react from '@vitejs/plugin-react';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [react()],
    server: {
        proxy: {
            '/api': {
                target: 'https://universal-technologies-linkedin-ai-production.up.railway.app',
                changeOrigin: true,
                secure: true,
            },
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'), // Alias for cleaner imports
        },
    },
    build: {
        outDir: 'dist', // Standard Vercel output directory
        manifest: true,         // Generate manifest.json
        rollupOptions: {
            input: {
                main: 'resources/js/index.jsx',
                styles: 'resources/css/app.css', // Explicitly define the CSS input
            },
        },
    },
});
