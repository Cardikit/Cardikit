import { useState } from 'react';
import { cardService } from '@/services/cardService';
import { extractErrorMessage } from '@/services/errorHandling';
import { ApiError } from '@/services/httpClient';

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
export const useDeleteCard = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const deleteCard = async (id: number) => {
        setLoading(true);
        setError(null);

        try {
            const response = await cardService.delete(id);
            return response;
        } catch (error: any) {
            if (error instanceof ApiError) {
                const data: any = error.data;
                const apiError =
                    data?.errors?.name?.[0] ??
                    data?.error ??
                    data?.message;
                setError(apiError || 'An unknown API error occurred. Please try again.');
            } else {
                setError(extractErrorMessage(error, 'Unexpected error occurred'));
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { deleteCard, loading, error };
}
