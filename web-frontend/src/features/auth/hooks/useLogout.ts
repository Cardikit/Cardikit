import { useState } from 'react';
import { authService } from '@/services/authService';
import { extractErrorMessage } from '@/services/errorHandling';

/**
* useLogout Hook
* --------------
* A custom hook to log out the currently authenticated user.
*
* Features:
* - Triggers POST request to `/logout` endpoint
* - Tracks loading and error states
* - Handles unexpected errors gracefully
*
* Usage:
* ```tsx
* const { logout, loading, error } = useLogout();
* await logout();
* ```
*
* Returns:
* - `logout`: async function to perform logout
* - `loading`: boolean indicating request in progress
* - `error`: error message string or null
*
* @since 0.0.1
*/
export const useLogout = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const logout = async () => {
        setLoading(true);
        setError(null);

        try {
            const response = await authService.logout();
            return response;
        } catch (error: any) {
            setError(extractErrorMessage(error, 'Unexpected error occurred'));
            return null as any;
        } finally {
            setLoading(false);
        }
    }

    return { logout, loading, error };
}
