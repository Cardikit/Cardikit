import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '@/contexts/AuthContext';
import Loading from '@/components/Loading';

/**
* GuestRoute
* ----------
* Prevents authenticated users from accessing guest-only pages (e.g. login/register).
*
* Logic:
* - If authentication is still loading, show the loading screen.
* - If user is not logged in, render the nested route via <Outlet />.
* - If user *is* logged in, redirect to dashboard.
*
* Usage:
* Wraps routes like `/login`, `/register`, `/` that should only be accessible to unauthenticated users.
*
* @since 0.0.1
*/
export default function GuestRoute() {
    const { user, loading } = useAuth();

    if (loading) return <Loading />

    return !user ? <Outlet /> : <Navigate to="/dashboard" replace />
}
