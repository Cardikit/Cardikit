import type { ItemType } from '@/types/card';
interface Payload {
    name: string;
    color: string;
    banner_image?: string | null;
    avatar_image?: string | null;
    card_items: ItemType[];
}
/**
* useRegisterUser Hook
* --------------------
* Handles user registration via the `/register` API endpoint.
*
* Features:
* - Sends `name`, `email`, and `password` to backend
* - Manages `loading` and `error` states
* - Extracts and sets backend validation messages if present
*
* Usage:
* ```tsx
* const { register, loading, error } = useRegisterUser();
* await register({ name, email, password });
* ```
*
* Returns:
* - `register`: async function to trigger user registration
* - `loading`: boolean state for UI feedback
* - `error`: backend validation message or generic error
*
* @since 0.0.1
*/
export declare const useUpdateCard: () => {
    updateCard: (data: Payload, id: number) => Promise<any>;
    loading: boolean;
    error: string | null;
};
export {};
