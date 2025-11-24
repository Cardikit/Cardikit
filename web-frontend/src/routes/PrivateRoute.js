import { jsx as _jsx } from "react/jsx-runtime";
import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '@/contexts/AuthContext';
import Loading from '@/components/Loading';
/**
* PrivateRoute
* ------------
* Protects routes that require authentication.
*
* Logic:
* - While auth state is loading, show a loading indicator.
* - If user is authenticated, render the nested route via <Outlet />.
* - If not authenticated, redirect to the login page.
*
* Usage:
* Wraps any route that should only be accessible to logged-in users (e.g. /dashboard).
*
* @since 0.0.1
*/
export default function PrivateRoute() {
    const { user, loading } = useAuth();
    if (loading)
        return _jsx(Loading, {});
    return user ? _jsx(Outlet, {}) : _jsx(Navigate, { to: "/login", replace: true });
}
