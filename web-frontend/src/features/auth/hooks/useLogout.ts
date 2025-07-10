import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';

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
            const response = await api.post('/logout');
            return response.data;
        } catch (error: any) {
            if (axios.isAxiosError(error)) {
                // Check for specific backend error
                console.log(error.response?.data);
            } else {
                setError('Unexpected error occurred');
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { logout, loading, error };
}
