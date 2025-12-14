import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuth } from '@/contexts/AuthContext';
import Loading from '@/components/Loading';

const PRO_ROLE_THRESHOLD = 3;
const ADMIN_ROLE = 2;

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

    const role = user?.role ?? 0;
    const isPro = role >= PRO_ROLE_THRESHOLD || role === ADMIN_ROLE;

    return isPro
        ? <Outlet />
        : <Navigate to="/upgrade" replace state={{ from: location.pathname }} />;
}
