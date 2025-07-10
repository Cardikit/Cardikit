import { createContext, useContext } from 'react';
import { useAuthenticatedUser } from '@/hooks/useAuthenticatedUser';
import type { User } from '@/types/user';

interface AuthContextType {
    user: User | null
    loading: boolean
    refresh: () => Promise<void>
}

/**
* A React context that stores the authenticated user and loading state.
*
* @default user is null, loading is true
*
* @since 0.0.1
*/
const AuthContext = createContext<AuthContextType>({ user: null, loading: true, refresh: async () => {} });

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
export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
    const { user, loading, refresh } = useAuthenticatedUser();

    return (
        <AuthContext.Provider value={{ user, loading, refresh }}>
            {children}
        </AuthContext.Provider>
    )
}

/**
* A custom hook to access the authentication context.
*
* Useful for getting `user` and `loading` state anywhere in the app.
*
* @returns {{ user: any, loading: boolean }}
*
* @since 0.0.1
*/
export const useAuth = () => useContext(AuthContext);
