import type { User } from '@/types/user';
interface AuthContextType {
    user: User | null;
    loading: boolean;
    refresh: () => Promise<void>;
}
/**
* Wraps the application and provides authentication context to its children.
*
* Internally, it uses `useAuthenticatedUser()` to get the current user and loading state,
* and then makes that data available through React Context to anything inside.
*
* @param children React components that need access to auth state.
*
* @returns JSX with AuthContext.Provider wrapped around children.
*
* @since 0.0.1
*/
export declare const AuthProvider: ({ children }: {
    children: React.ReactNode;
}) => import("react/jsx-runtime").JSX.Element;
/**
* A custom hook to access the authentication context.
*
* Useful for getting `user` and `loading` state anywhere in the app.
*
* @returns {{ user: any, loading: boolean }}
*
* @since 0.0.1
*/
export declare const useAuth: () => AuthContextType;
export {};
