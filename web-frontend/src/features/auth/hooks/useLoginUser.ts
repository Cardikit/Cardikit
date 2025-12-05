import { useState } from 'react';
import { authService } from '@/services/authService';
import { extractErrorMessage } from '@/services/errorHandling';
import { ApiError } from '@/services/httpClient';

interface LoginPayload {
    email: string;
    password: string;
}

/**
* useLoginUser Hook
* -----------------
* A custom hook for handling user login via the `/login` endpoint.
*
* Features:
* - Tracks loading and error states
* - Sends login request with email and password
* - Provides error feedback from API (e.g. 422 validation errors)
*
* Usage:
* ```tsx
* const { login, loading, error } = useLoginUser();
* await login({ email, password });
* ```
*
* Returns:
* - `login`: async function to initiate login
* - `loading`: boolean indicating request in progress
* - `error`: string | null with error message (if any)
*
* @since 0.0.1
*/
export const useLoginUser = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const login = async (data: LoginPayload) => {
        setLoading(true);
        setError(null);

        try {
            const response = await authService.login({
                email: data.email,
                password: data.password
            });
            return response;
        } catch (error: any) {
            if (error instanceof ApiError && error.data && typeof error.data === 'object' && 'error' in error.data) {
                setError(String((error.data as any).error));
            } else {
                setError(extractErrorMessage(error, 'Unexpected error occurred'));
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { login, loading, error };
}
