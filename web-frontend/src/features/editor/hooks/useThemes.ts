import { useEffect, useState } from 'react';
import api from '@/lib/axios';
import type { ThemeMeta } from '@/types/theme';

export const useThemes = () => {
    const [themes, setThemes] = useState<ThemeMeta[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        if (typeof window !== 'undefined') {
            fetchThemes();
        }
    }, []);

    const fetchThemes = async () => {
        try {
            const response = await api.get<ThemeMeta[]>('/themes');
            setThemes(response.data);
        } catch (err: any) {
            setError(err?.response?.data?.message || 'Failed to load themes');
            setThemes([]);
        } finally {
            setLoading(false);
        }
    };

    return { themes, loading, error };
};
