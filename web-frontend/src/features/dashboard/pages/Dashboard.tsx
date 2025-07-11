import { useLogout } from '@/features/auth/hooks/useLogout';
import { useAuth } from '@/contexts/AuthContext';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
import BottomNav from '@/features/dashboard/components/BottomNav';
import TopNav from '@/features/dashboard/components/TopNav';

const Dashboard: React.FC = () => {
    const { logout, loading, error } = useLogout();
    const { refresh, user } = useAuth();

    const onLogout = async () => {
        try {
            await fetchCsrfToken();
            await logout();
            await refresh();
        } catch (err) {
            console.log(err);
        }
    }

    return (
        <div className="min-h-dvh bg-gray-300 pt-16">
            <TopNav />
            {error && <p>{error}</p>}
            <h1>Dashboard</h1>
            <p>Hello {user?.name}</p>
            <button onClick={onLogout}>
                {loading ? 'Loading...' : 'Logout'}
            </button>
            <BottomNav />
        </div>
    );
}

export default Dashboard;
