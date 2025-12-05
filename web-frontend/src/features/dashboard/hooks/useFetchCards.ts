import { useEffect, useState } from 'react';
import type { CardType } from '@/types/card';
import { cardService } from '@/services/cardService';
import { extractErrorMessage } from '@/services/errorHandling';

export const useFetchCards = () => {
    const [cards, setCards] = useState<CardType[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        fetchCards();
    }, []);

    const refresh = async () => {
        setLoading(true);
        await fetchCards();
    };

    const fetchCards = async () => {
        try {
            const response = await cardService.list();
            setCards(response);
        } catch (err: any) {
            setError(extractErrorMessage(err, 'Failed to fetch cards'));
            setCards([]);
        } finally {
            setLoading(false);
        }
    };

    return { cards, refresh, loading, error };
};
