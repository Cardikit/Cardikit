import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';

interface LoginPayload {
    email: string;
    password: string;
}

export const useLoginUser = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const login = async (data: LoginPayload) => {
        setLoading(true);
        setError(null);

        try {
            const response = await api.post('/login', {
                email: data.email,
                password: data.password
            });
            return response.data;
        } catch (error: any) {
            if (axios.isAxiosError(error)) {
                // Check for specific backend error (e.g. 422 conflict)
                if (error.response?.data?.error) {
                    setError(error.response?.data?.error);
                }
            } else {
                setError('Unexpected error occurred');
            }
            throw error;
        } finally {
            setLoading(false);
        }
    }

    return { login, loading, error };
}
