import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';
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
    const [error, setError] = useState(null);
    const deleteCard = async (id) => {
        setLoading(true);
        setError(null);
        try {
            const response = await api.delete(`/@me/cards/${id}`);
            return response.data;
        }
        catch (error) {
            if (axios.isAxiosError(error)) {
                // Check for specific backend error (e.g. 422 conflict)
                if (error.response?.data?.errors?.name) {
                    setError(error.response?.data?.errors?.name[0]);
                }
                if (error.response?.data?.error) {
                    setError(error.response?.data?.error);
                }
                else {
                    setError('An unknown API error occurred. Please try again.');
                }
            }
            else {
                setError('Unexpected error occurred');
            }
            throw error;
        }
        finally {
            setLoading(false);
        }
    };
    return { deleteCard, loading, error };
};
