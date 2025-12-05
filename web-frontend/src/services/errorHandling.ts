import { ApiError } from './httpClient';
import { ValidationError } from 'yup';

export const extractErrorMessage = (error: unknown, fallback = 'Something went wrong'): string => {
    if (error instanceof ApiError) {
        return error.message || fallback;
    }

    if (error instanceof ValidationError) {
        return error.errors[0] ?? fallback;
    }

    if (error instanceof Error) {
        return error.message || fallback;
    }

    return fallback;
};
