import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';
import type { ItemType } from '@/types/card';

interface Payload {
    name: string;
    card_items: ItemType[]
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
export const useUpdateCard = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const updateCard = async (data: Payload, id: number) => {
        setLoading(true);
        setError(null);

        try {
            const response = await api.put(`/@me/cards/${id}`, {
                name: data.name,
                card_items: data.card_items
            });
            return response.data;
        } catch (error: any) {
            if (axios.isAxiosError(error)) {
                // Check for specific backend error (e.g. 422 conflict)
                if (error.response?.data?.errors?.name) {
                    setError(error.response?.data?.errors?.name[0]);
                }
                if (error.response?.data?.error) {
                    setError(error.response?.data?.error);
                } else {
                    setError('An unknown API error occurred. Please try again.');
                }
            } else {
                setError('Unexpected error occurred');
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { updateCard, loading, error };
}
