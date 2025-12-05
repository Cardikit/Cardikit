import { httpClient, ApiError } from './httpClient';
import type { User } from '@/types/user';

export interface LoginPayload {
    email: string;
    password: string;
}

export interface RegisterPayload {
    name: string;
    email: string;
    password: string;
}

export const authService = {
    me: async (): Promise<User> => httpClient.get<User>('/@me'),

    login: (payload: LoginPayload) => httpClient.post<{ message: string }>('/login', payload),

    register: (payload: RegisterPayload) => httpClient.post<{ message: string }>('/register', payload),

    logout: () => httpClient.post<{ message: string }>('/logout'),
};
