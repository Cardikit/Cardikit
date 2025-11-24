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
export declare const useAuthenticatedUser: () => {
    user: User | null;
    refresh: () => Promise<void>;
    loading: boolean;
    error: string | null;
};
