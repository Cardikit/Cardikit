import api from '@/lib/axios';

export const fetchCsrfToken = async () => {
    try {
        const res = await api.get('/csrf-token');
        const token = res.data.csrf_token;

        api.defaults.headers.common['X-CSRF-TOKEN'] = token;
    } catch (err) {
        console.error('Failed to fetch CSRF token', err);
    }
};
