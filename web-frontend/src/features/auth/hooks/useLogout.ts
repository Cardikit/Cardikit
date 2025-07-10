import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';

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
