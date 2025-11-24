interface LoginPayload {
    email: string;
    password: string;
}
/**
* useLoginUser Hook
* -----------------
* A custom hook for handling user login via the `/login` endpoint.
*
* Features:
* - Tracks loading and error states
* - Sends login request with email and password
* - Provides error feedback from API (e.g. 422 validation errors)
*
* Usage:
* ```tsx
* const { login, loading, error } = useLoginUser();
* await login({ email, password });
* ```
*
* Returns:
* - `login`: async function to initiate login
* - `loading`: boolean indicating request in progress
* - `error`: string | null with error message (if any)
*
* @since 0.0.1
*/
export declare const useLoginUser: () => {
    login: (data: LoginPayload) => Promise<any>;
    loading: boolean;
    error: string | null;
};
export {};
