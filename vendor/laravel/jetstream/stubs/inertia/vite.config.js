import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true, // Enables hot module replacement (HMR)
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null, // Base URL for relative asset paths
                    includeAbsolute: false, // Whether to include absolute asset paths
                },
            },
        }),
    ],
    build: {
        outDir: 'dist', // Specifies the output directory for the build
        emptyOutDir: true, // Optional: Clears the directory before each build
    },
});
