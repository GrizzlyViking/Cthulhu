import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        })
    ],
    server: {
        https: {
            key: "/Users/sebastian/.config/valet/Certificates/cthulhu.test.key",
            cert: "/Users/sebastian/.config/valet/Certificates/cthulhu.test.crt"
        },
        host: 'cthulhu.test',
    },
    test: {
        environment: 'jsdom', // or 'happy-dom'
        setupFiles: ['tests/vitest.setup.js'],
        globals: true,
    },
});
