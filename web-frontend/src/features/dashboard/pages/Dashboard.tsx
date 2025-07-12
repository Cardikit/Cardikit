import { useState } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import BottomNav from '@/features/dashboard/components/BottomNav';
import TopNav from '@/features/dashboard/components/TopNav';
import NavMenu from '@/features/dashboard/components/NavMenu';

const Dashboard: React.FC = () => {
    const { user } = useAuth();
    const [open, setOpen] = useState(false);

    const toggleMenu = () => {
        setOpen(prev => !prev);
    }

    return (
        <div className="min-h-dvh bg-gray-300 pt-16 overflow-x-hidden">
            <TopNav openMenu={toggleMenu} />
            <h1>Dashboard</h1>
            <p>Hello {user?.name}</p>
            <BottomNav />
            <NavMenu open={open} closeMenu={toggleMenu} />
        </div>
    );
}

export default Dashboard;
