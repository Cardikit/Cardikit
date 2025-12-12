import Logo from '@/assets/logo.webp';
import { ImEmbed2 } from 'react-icons/im';
import { FaNfcSymbol } from 'react-icons/fa6';
import { FaChartBar, FaUserFriends, FaAddressCard } from 'react-icons/fa';
import { IoPersonSharp, IoLogOut, IoClose } from "react-icons/io5";
import { useLogout } from '@/features/auth/hooks/useLogout';
import { useAuth } from '@/contexts/AuthContext';
import { Link, useLocation } from 'react-router-dom';

/**
 * NavMenu
 * -------
 * Mobile/overlay navigation:
 * - Mirrors DesktopNav links for small screens.
 * - Provides logout with auth refresh.
 * - Handles open/close backdrop interactions.
 *
 * @since 0.0.2
 */
interface NavMenuProps {
    open: boolean;
    closeMenu: () => void;
}

const NavMenu: React.FC<NavMenuProps> = ({ open, closeMenu }) => {
    const { logout } = useLogout();
    const { refresh } = useAuth();

    const location = useLocation();
    const isActive = (path: string) => location.pathname === path;

    const onLogout = async () => {
        try {
            await logout();
            await refresh();
        } catch (err) {
            console.log(err);
        }
    }

    return (
        <>
            {/* Backdrop */}
            {open && (
                <div
                    onClick={closeMenu}
                    className="fixed top-0 left-0 w-full h-dvh bg-black opacity-40 z-40"
                />
            )}

            {/* Sliding Menu */}
            <nav
                className={`fixed top-0 left-0 h-dvh w-3/4 md:w-1/3 bg-gray-100 z-50 shadow-md transition-transform duration-300 ease-in-out ${
                    open ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="p-4 flex flex-col space-y-4">
                    <div className="flex justify-between items-center pb-6">
                        <img src={Logo} alt="Logo" className="w-12" />
                        <IoClose onClick={closeMenu} className="text-2xl text-gray-800 cursor-pointer" />
                    </div>
                    <p className="text-sm text-gray-600 font-inter">Connect Devices</p>
                    <Link to="/coming-soon" onClick={closeMenu} className={`flex items-center space-x-2 hover:text-primary-700 ${isActive('/coming-soon') ? 'text-primary-500' : 'text-gray-800'}`}>
                        <ImEmbed2 className="text-xl" />
                        <p className="font-inter">Get Embeddable Widget</p>
                    </Link>
                    <Link to="/coming-soon" onClick={closeMenu} className={`flex items-center space-x-2 hover:text-primary-700 ${isActive('/coming-soon') ? 'text-primary-500' : 'text-gray-800'}`}>
                        <FaNfcSymbol className="text-xl" />
                        <p className="font-inter">Pair NFC device</p>
                    </Link>
                    <hr className="border-gray-300" />
                    <p className="text-sm text-gray-600 font-inter">Account</p>
                    <Link to="/" onClick={closeMenu} className={`flex items-center space-x-2 hover:text-primary-700 ${isActive('/') ? 'text-primary-500' : 'text-gray-800'}`}>
                        <FaAddressCard className="text-xl" />
                        <p className="font-inter">My cards</p>
                    </Link>
                    <Link to="/contacts" onClick={closeMenu} className={`flex items-center space-x-2 hover:text-primary-700 ${isActive('/contacts') ? 'text-primary-500' : 'text-gray-800'}`}>
                        <FaUserFriends className="text-xl" />
                        <p className="font-inter">Contacts</p>
                    </Link>
                    <Link to="/analytics" onClick={closeMenu} className={`flex items-center space-x-2 hover:text-primary-700 ${isActive('/analytics') ? 'text-primary-500' : 'text-gray-800'}`}>
                        <FaChartBar className="text-xl" />
                        <p className="font-inter">Analytics</p>
                    </Link>
                    <Link to="/account" onClick={closeMenu} className={`flex items-center space-x-2 hover:text-primary-700 ${isActive('/account') ? 'text-primary-500' : 'text-gray-800'}`}>
                        <IoPersonSharp className="text-xl" />
                        <p className="font-inter">Manage account</p>
                    </Link>
                    <div onClick={onLogout} className="flex items-center space-x-2 cursor-pointer text-gray-800">
                        <IoLogOut className="text-xl" />
                        <p className="font-inter">Logout</p>
                    </div>
                </div>
            </nav>
        </>
    );
};

export default NavMenu;
