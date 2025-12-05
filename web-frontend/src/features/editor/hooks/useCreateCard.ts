import { useState } from 'react';
import type { ItemType } from '@/types/card';
import * as yup from 'yup';
import { cardService } from '@/services/cardService';
import { extractErrorMessage } from '@/services/errorHandling';
import { ApiError } from '@/services/httpClient';

/**
 * useCreateCard
 * -------------
 * Client-side hook for validating and creating a card via the cardService.
 *
 * - Runs yup validation before hitting the API.
 * - Normalizes error messages from validation and ApiError responses.
 * - Exposes loading/error state for UI feedback.
 *
 * @since 0.0.2
 */

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
export const useCreateCard = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const createCard = async (data: Payload) => {
        setLoading(true);
        setError(null);

        try {
            const response = await cardService.create({
                name: data.name,
                color: data.color,
                theme: data.theme,
                banner_image: data.banner_image ?? null,
                avatar_image: data.avatar_image ?? null,
                card_items: data.card_items
            });
            return response;
        } catch (error: any) {
            if (error instanceof yup.ValidationError) {
                setError(error.errors[0]);
            } else if (error instanceof ApiError) {
                const data: any = error.data;
                const apiError =
                    data?.errors?.name?.[0] ??
                    data?.message ??
                    data?.error;
                setError(apiError || 'An unknown API error occurred. Please try again.');
            } else {
                setError(extractErrorMessage(error, 'Unexpected error occurred'));
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { createCard, loading, error };
}
