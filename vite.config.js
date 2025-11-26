import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        proxy: {
            '/api': {
                target: 'http://localhost:8000',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, ''),
            },
        },
    },
    build: {
        chunkSizeWarningLimit: 2000,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('@inertiajs')) {
                            return 'inertia';
                        }
                        if (id.includes('vue')) {
                            return 'vue';
                        }
                        if (id.includes('axios')) {
                            return 'axios';
                        }
                        if (id.includes('ziggy-js')) {
                            return 'ziggy';
                        }
                        return 'vendor';
                    }
                },
            },
        },
    },
});
