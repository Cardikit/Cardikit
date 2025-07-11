import { useLogout } from '@/features/auth/hooks/useLogout';
import { useAuth } from '@/contexts/AuthContext';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';

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
        <div>
            {error && <p>{error}</p>}
            <h1>Dashboard</h1>
            <p>Hello {user?.name}</p>
            <button onClick={onLogout}>
                {loading ? 'Loading...' : 'Logout'}
            </button>
        </div>
    );
}

export default Dashboard;
