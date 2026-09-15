import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
    test: {
        // Nested agent worktrees have their own dependencies and are not this suite.
        include: ['tests/**/*.spec.{js,ts}'],
        environment: 'jsdom',
        setupFiles: ['tests/vitest.setup.js'],
        globals: true,
    },
})
