import type { CardType } from '@/types/card';
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
export declare const useFetchCards: () => {
    cards: CardType[];
    refresh: () => Promise<void>;
    loading: boolean;
    error: string | null;
};
