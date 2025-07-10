import { useState } from 'react';
import api from '@/lib/axios';
import axios from 'axios';

interface RegisterPayload {
    name: string;
    email: string;
    password: string;
}

export const useRegisterUser = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const register = async (data: RegisterPayload) => {
        setLoading(true);
        setError(null);

        try {
            const response = await api.post('/register', {
                name: data.name,
                email: data.email,
                password: data.password
            });
            return response.data;
        } catch (error: any) {
            if (axios.isAxiosError(error)) {
                // Check for specific backend error (e.g. 422 conflict)
                if (error.response?.data?.errors?.email) {
                    setError(error.response?.data?.errors?.email[0]);
                }
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

    return { register, loading, error };
}
