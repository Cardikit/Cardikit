import { FaAddressCard, FaUserFriends, FaChartBar } from 'react-icons/fa';
import { Link, useLocation } from 'react-router-dom';

/**
 * BottomNav
 * ---------
 * Mobile-only navigation bar for the Cardikit dashboard.
 *
 * Responsibilities:
 * - Provides quick access to the primary app sections:
 *   - "My Cards" → `/`
 *   - "Contacts" → `/coming-soon` (placeholder for future features)
 * - Highlights the active route using `useLocation` to match the current path.
 * - Renders a fixed bottom bar with icons and labels, optimized for touch.
 * - Automatically hidden on large screens (`lg:hidden`) since desktop
 *   layouts use a sidebar or top navigation instead.
 *
 * Visual notes:
 * - Uses bold icons (`FaAddressCard`, `FaUserFriends`) sized for mobile.
 * - Active items are tinted with the primary color and disable pointer cursor.
 * - Includes a top border and shadow for separation from page content.
 *
 * @component
 * @since 0.0.2
 */
const BottomNav: React.FC = () => {
    const location = useLocation();
    const isActive = (path: string) => location.pathname === path;

    return (
        <div className="fixed bottom-0 w-full bg-background-100 shadow-md z-10 flex justify-around items-center py-4 border-t border-gray-200 lg:hidden">
            <Link
                to="/"
                className={`flex flex-col items-center ${isActive('/') ? 'text-primary-500 cursor-default' : 'text-gray-800'}`}
            >
                <FaAddressCard className="text-3xl" />
                <span className="text-xs font-inter">My Cards</span>
            </Link>
            <Link
                to="/contacts"
                className={`flex flex-col items-center ${isActive('/contacts') ? 'text-primary-500 cursor-default' : 'text-gray-500'}`}
            >
                <FaUserFriends className="text-3xl" />
                <span className="text-xs font-inter">Contacts</span>
            </Link>
            <Link
                to="/analytics"
                className={`flex flex-col items-center ${isActive('/analytics') ? 'text-primary-500 cursor-default' : 'text-gray-500'}`}
            >
                <FaChartBar className="text-3xl" />
                <span className="text-xs font-inter">Analytics</span>
            </Link>
        </div>
    );
}

export default BottomNav;
