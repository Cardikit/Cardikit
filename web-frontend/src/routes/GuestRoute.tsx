import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '@/contexts/AuthContext';
import Loading from '@/components/Loading';

export default function GuestRoute() {
    const { user, loading } = useAuth();

    if (loading) return <Loading />

    return !user ? <Outlet /> : <Navigate to="/dashboard" replace />
}
