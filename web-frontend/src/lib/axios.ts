import axios from 'axios';

/**
* Creates an axios instance with the base URL and credentials set.
*
* @returns {Object} An axios instance with the base URL and credentials set.
*
* @since 0.0.1
*/
const api = axios.create({
    baseURL: 'http://localhost/api/v1',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json'
    }
});

export default api;
