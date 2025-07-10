import api from '@/lib/axios';

/**
* Utility function to fetch the CSRF token from the server
*
* Sets the `X-CSRF-TOKEN` header in the axios instance
*
* NOTE: Use this utility before each `POST`, `PUT`, or `DELETE` request
*
* @since 0.0.1
*/
export const fetchCsrfToken = async () => {
    try {
        const res = await api.get('/csrf-token');
        const token = res.data.csrf_token;

        api.defaults.headers.common['X-CSRF-TOKEN'] = token;
    } catch (err) {
        console.error('Failed to fetch CSRF token', err);
    }
};
