import { FaAddressCard, FaUserFriends } from 'react-icons/fa';
import { Link, useLocation } from 'react-router-dom';

const BottomNav: React.FC = () => {
    const location = useLocation();
    const isActive = (path: string) => location.pathname === path;

    return (
        <div className="fixed bottom-0 w-full bg-background-100 shadow-md z-10 flex justify-around items-center py-4 border-t border-gray-200">
            <Link
                to="/dashboard"
                className={`flex flex-col items-center ${isActive('/dashboard') ? 'text-primary-500 cursor-default' : 'text-gray-800'}`}
            >
                <FaAddressCard className="text-3xl" />
                <span className="text-xs font-inter">My Cards</span>
            </Link>
            <Link
                to="/dashboard/contacts"
                className={`flex flex-col items-center ${isActive('/dashboard/contacts') ? 'text-primary-500 cursor-default' : 'text-gray-500'}`}
            >
                <FaUserFriends className="text-3xl" />
                <span className="text-xs font-inter">Contacts</span>
            </Link>
        </div>
    );
}

export default BottomNav;
