import { useEffect, useState } from 'react';
import api from '@/lib/axios';
import type { CardType } from '@/types/card';

/**
* This react hook fetches the currently authenticated user from the API.
* It manages the loading state, any potential errors, and stores the user data if available.
*
* Great for components that depend on knowing if a user is logged in or want to show user info.
*
* @returns {{
*   user: AuthUser | null;
*   loading: boolean;
*   error: string | null;
* }}
*
* @since 0.0.1
*/
export const useFetchCards = () => {
    const [cards, setCards] = useState<CardType[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    /**
    * Run fetchAuthenticatedUser on component mount.
    *
    * @since 0.0.1
    */
    useEffect(() => {
        fetchCards();
    }, []);

    const refresh = async () => {
        setLoading(true);
        await fetchCards();
    };

    /**
    * Contacts the `/@me` endpoint to get the authenticated user's data.
    *
    * If the request succeeds, it stores the user info in state.
    * If it fails (e.g., not logged in or server error), it sets an error message and clears the user.
    * Regardless of outcome, it turns off the loading flag once the request is done.
    *
    * @since 0.0.1
    */
    const fetchCards = async () => {
        try {
            const response = await api.get<CardType[]>('/@me/cards');
            const normalized = response.data.map(card => ({
                ...card,
                color: card.color ?? '#1D4ED8',
            }));
            setCards(normalized);
        } catch (err: any) {
            setError(err?.response?.data?.message || 'Failed to fetch cards');
            setCards([]);
        } finally {
            setLoading(false);
        }
    };

    return { cards, refresh, loading, error };
};
