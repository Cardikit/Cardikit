import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuth } from '@/contexts/AuthContext';
import Loading from '@/components/Loading';

const PRO_ROLE_THRESHOLD = 2;

/**
 * ProRoute
 * --------
 * Protects routes that require a Pro subscription (role >= PRO_ROLE_THRESHOLD).
 *
 * - Shows loading while auth is resolving.
 * - Redirects non-pro users to the dashboard.
 */
export default function ProRoute() {
    const { user, loading } = useAuth();
    const location = useLocation();

    if (loading) return <Loading />;

    const isPro = (user?.role ?? 0) >= PRO_ROLE_THRESHOLD;

    return isPro
        ? <Outlet />
        : <Navigate to="/upgrade" replace state={{ from: location.pathname }} />;
}
