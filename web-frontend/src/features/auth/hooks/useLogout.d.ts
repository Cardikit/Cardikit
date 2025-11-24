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
export declare const useLogout: () => {
    logout: () => Promise<any>;
    loading: boolean;
    error: string | null;
};
