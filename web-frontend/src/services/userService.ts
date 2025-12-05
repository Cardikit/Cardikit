import { httpClient } from './httpClient';
import type { User } from '@/types/user';

export interface UpdateAccountPayload {
    current_password: string;
    name?: string;
    email?: string;
    password?: string;
    password_confirmation?: string;
}

export const userService = {
    updateAccount: (payload: UpdateAccountPayload) =>
        httpClient.put<{ message: string; user: User }>('/@me', payload),

    deleteAccount: (password: string) =>
        httpClient.delete<{ message: string }>('/@me', { password }),
};
