import axios, { type Method } from 'axios';
import api from '@/lib/axios';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';

export class ApiError extends Error {
    status?: number;
    data?: unknown;
    constructor(message: string, status?: number, data?: unknown) {
        super(message);
        this.status = status;
        this.data = data;
    }
}

const needsCsrf = (method: Method) => ['post', 'put', 'delete', 'patch'].includes(method.toLowerCase());

export const toApiError = (err: unknown): ApiError => {
    if (err instanceof ApiError) return err;

    if (axios.isAxiosError(err)) {
        const status = err.response?.status;
        const data = err.response?.data;
        const message =
            (data?.message as string | undefined) ||
            (data?.error as string | undefined) ||
            err.message ||
            'Request failed';
        return new ApiError(message, status, data);
    }

    const fallbackMessage = err instanceof Error ? err.message : 'Unexpected error';
    return new ApiError(fallbackMessage);
};

const request = async <T>(method: Method, url: string, data?: any): Promise<T> => {
    if (needsCsrf(method)) {
        await fetchCsrfToken();
    }

    try {
        const response = await api.request<T>({ method, url, data });
        return response.data;
    } catch (err) {
        throw toApiError(err);
    }
};

export const httpClient = {
    get: <T>(url: string) => request<T>('get', url),
    post: <T>(url: string, data?: any) => request<T>('post', url, data),
    put: <T>(url: string, data?: any) => request<T>('put', url, data),
    delete: <T>(url: string, data?: any) => request<T>('delete', url, data),
};
