import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';
import type { ItemType } from '@/types/card';
import * as yup from 'yup';
import { cardSchema } from '@/features/editor/validationSchema';

interface Payload {
    name: string;
    color: string;
    theme?: string;
    banner_image?: string | null;
    avatar_image?: string | null;
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
            await cardSchema.validate(data, { abortEarly: false });

            const response = await api.put(`/@me/cards/${id}`, {
                name: data.name,
                color: data.color,
                theme: data.theme,
                banner_image: data.banner_image ?? null,
                avatar_image: data.avatar_image ?? null,
                card_items: data.card_items
            });
            return response.data;
        } catch (error: any) {
            if (error instanceof yup.ValidationError) {
                setError(error.errors[0]);
            } else if (axios.isAxiosError(error)) {
                // Check for specific backend error (e.g. 422 conflict)
                if (error.response?.data?.errors?.name) {
                    setError(error.response?.data?.errors?.name[0]);
                } else if (error.response?.data?.message) {
                    setError(error.response?.data?.message);
                } else if (error.response?.data?.error) {
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
