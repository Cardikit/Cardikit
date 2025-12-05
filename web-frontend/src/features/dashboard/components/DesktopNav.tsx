import Logo from '@/assets/logo.webp';
import { ImEmbed2 } from 'react-icons/im';
import { FaNfcSymbol } from 'react-icons/fa6';
import { IoPersonSharp, IoLogOut } from "react-icons/io5";
import { useLogout } from '@/features/auth/hooks/useLogout';
import { useAuth } from '@/contexts/AuthContext';
import { Link } from 'react-router-dom';

/**
 * DesktopNav
 * ----------
 * Left-hand navigation for large screens:
 * - Quick links to widget/NFC placeholders and account page.
 * - Triggers logout via auth hooks, then refreshes auth context.
 * - Hidden on mobile in favor of NavMenu.
 *
 * @since 0.0.2
 */
const DesktopNav = () => {

    const { logout } = useLogout();
    const { refresh } = useAuth();

    const onLogout = async () => {
        try {
            await logout();
            await refresh();
        } catch (err) {
            console.log(err);
        }
    }
    return (
        <div className="hidden lg:flex flex-col lg:fixed top-0 left-0 h-dvh w-60 2xl:w-80 z-50 bg-gray-100 p-4 shadow-md">
            <div className="w-full flex flex-col space-y-6">
                <img src={Logo} alt="Cardikit Logo" className="w-12" />
                <p className="text-sm text-gray-600 font-inter">Connect Devices</p>
                <Link to="/coming-soon" className="flex items-center space-x-2 hover:text-primary-700">
                    <ImEmbed2 className="text-xl text-gray-800" />
                    <p className="font-inter text-gray-800">Get Embeddable Widget</p>
                </Link>
                <Link to="/coming-soon" className="flex items-center space-x-2 hover:text-primary-700">
                    <FaNfcSymbol className="text-xl text-gray-800" />
                    <p className="font-inter text-gray-800">Pair NFC device</p>
                </Link>
                <hr className="border-gray-300" />
                <p className="text-sm text-gray-600 font-inter">Account</p>
                <Link to="/account" className="flex items-center space-x-2 hover:text-primary-700">
                    <IoPersonSharp className="text-xl text-gray-800" />
                    <p className="font-inter text-gray-800">Manage account</p>
                </Link>
                <div onClick={onLogout} className="flex items-center space-x-2 cursor-pointer">
                    <IoLogOut className="text-xl text-gray-800" />
                    <p className="font-inter text-gray-800">Logout</p>
                </div>
            </div>
        </div>
    );
}

export default DesktopNav;
