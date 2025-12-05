import { useEffect, useState } from 'react';
import type { ThemeMeta } from '@/types/theme';
import { cardService } from '@/services/cardService';
import { extractErrorMessage } from '@/services/errorHandling';

/**
 * useThemes
 * ---------
 * Fetches the list of available card themes from the backend.
 *
 * Responsibilities:
 * - Requests theme metadata via `cardService.themes()`.
 * - Manages loading and error states for UI consumption.
 * - Safely handles SSR by skipping fetches when `window` is unavailable.
 * - Cancels state updates if the component unmounts mid-request.
 *
 * Returns:
 * - `themes`  → Array of theme metadata for theme selection components.
 * - `loading` → Indicates whether the themes request is in progress.
 * - `error`   → Populated when fetching fails (friendly string message).
 *
 * Notes:
 * - Uses a `cancelled` flag to avoid React "setState on unmounted component"
 *   warnings during slow network requests.
 * - Defaults to an empty theme list on failure.
 *
 * @hook
 * @since 0.0.2
 */
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
                const response = await cardService.themes();
                if (!cancelled) {
                    setThemes(response);
                }
            } catch (err: any) {
                if (!cancelled) {
                    setError(extractErrorMessage(err, 'Failed to load themes'));
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
