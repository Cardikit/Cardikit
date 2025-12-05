import { useState } from 'react';
import { authService } from '@/services/authService';
import { extractErrorMessage } from '@/services/errorHandling';
import { ApiError } from '@/services/httpClient';

interface RegisterPayload {
    name: string;
    email: string;
    password: string;
}

/**
* useRegisterUser Hook
* --------------------
* Handles user registration via the `/register` API endpoint.
*
* Features:
* - Sends `name`, `email`, and `password` to backend
* - Manages `loading` and `error` states
* - Extracts and sets backend validation messages if present
*
* Usage:
* ```tsx
* const { register, loading, error } = useRegisterUser();
* await register({ name, email, password });
* ```
*
* Returns:
* - `register`: async function to trigger user registration
* - `loading`: boolean state for UI feedback
* - `error`: backend validation message or generic error
*
* @since 0.0.1
*/
export const useRegisterUser = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const register = async (data: RegisterPayload) => {
        setLoading(true);
        setError(null);

        try {
            const response = await authService.register({
                name: data.name,
                email: data.email,
                password: data.password
            });
            return response;
        } catch (error: any) {
            if (error instanceof ApiError) {
                const data: any = error.data;
                if (data?.errors?.email) {
                    setError(data.errors.email[0]);
                } else if (data?.error || data?.message) {
                    setError(data.error || data.message);
                } else {
                    setError(extractErrorMessage(error, 'An unknown API error occurred. Please try again.'));
                }
            } else {
                setError(extractErrorMessage(error, 'Unexpected error occurred'));
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { register, loading, error };
}
