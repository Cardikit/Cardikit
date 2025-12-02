import { useEffect, useState } from 'react';
import api from '@/lib/axios';
import type { ThemeMeta } from '@/types/theme';

export const useThemes = () => {
    const [themes, setThemes] = useState<ThemeMeta[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const isBrowser = typeof window !== 'undefined';

    useEffect(() => {
        if (!isBrowser) {
            setLoading(false);
            return;
        }

        let cancelled = false;
        const fetchThemes = async () => {
            try {
                const response = await api.get<ThemeMeta[]>('/themes');
                if (!cancelled) {
                    setThemes(response.data);
                }
            } catch (err: any) {
                if (!cancelled) {
                    setError(err?.response?.data?.message || 'Failed to load themes');
                    setThemes([]);
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        };

        fetchThemes();

        return () => {
            cancelled = true;
        };
    }, [isBrowser]);

    return { themes, loading, error };
};
