import { useEffect, useState } from 'react';
import api from '@/lib/axios';
import type { User } from '@/types/user';

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
export const useAuthenticatedUser = () => {
    const [user, setUser] = useState<User | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    /**
    * Run fetchAuthenticatedUser on component mount.
    *
    * @since 0.0.1
    */
    useEffect(() => {
        fetchAuthenticatedUser();
    }, []);

    const refresh = async () => {
        setLoading(true);
        await fetchAuthenticatedUser();
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
    const fetchAuthenticatedUser = async () => {
        try {
            const response = await api.get<User>('/@me');
            setUser(response.data);
        } catch (err: any) {
            setError(err?.response?.data?.message || 'Failed to fetch user');
            setUser(null);
        } finally {
            setLoading(false);
        }
    };

    return { user, refresh, loading, error };
};
