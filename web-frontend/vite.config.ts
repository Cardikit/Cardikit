import { defineConfig, loadEnv } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'
import { fileURLToPath } from 'url';
import { dirname, resolve } from 'path';
import { configDefaults } from 'vitest/config';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const normalizeBasePath = (basePath?: string) => {
    if (!basePath) return '/app';

    const prefixed = basePath.startsWith('/') ? basePath : `/${basePath}`;
    const trimmed = prefixed.replace(/\/+$/, '');

    return trimmed || '/';
};

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const basePath = normalizeBasePath(env.VITE_APP_BASE_PATH || '/app');
    const devProxyTarget = env.VITE_DEV_PROXY_TARGET || 'http://cardikit_server';

    return {
        base: basePath.endsWith('/') ? basePath : `${basePath}/`,
        plugins: [react(), tailwindcss()],
        define: {
            'process.env': {
                GA_MEASUREMENT_ID: env.GA_MEASUREMENT_ID,
            },
        },
        test: {
            environment: 'jsdom',
            globals: true,
            exclude: [...configDefaults.exclude, 'e2e/**'],
            setupFiles: './vitest.setup.ts',
            css: false,
        },
        resolve: {
            alias: {
                '@': resolve(__dirname, './src'),
            },
        },
        server: {
            host: '0.0.0.0',
            port: 5173,
            proxy: {
                '/api': {
                    target: devProxyTarget,
                    changeOrigin: true,
                },
            },
        },
    };
});
