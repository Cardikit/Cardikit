const normalizeBasePath = (basePath?: string) => {
    if (!basePath) return '/app';

    const prefixed = basePath.startsWith('/') ? basePath : `/${basePath}`;
    const trimmed = prefixed.replace(/\/+$/, '');

    return trimmed || '/';
};

export const appBasePath = normalizeBasePath(import.meta.env.VITE_APP_BASE_PATH || '/app');

export const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || '/api/v1';
