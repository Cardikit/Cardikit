import Logo from '@/assets/logo.webp';
import { ImEmbed2 } from 'react-icons/im';
import { FaNfcSymbol } from 'react-icons/fa6';
import { IoPersonSharp, IoLogOut, IoClose } from "react-icons/io5";
import { useLogout } from '@/features/auth/hooks/useLogout';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
import { useAuth } from '@/contexts/AuthContext';

interface NavMenuProps {
    open: boolean;
    closeMenu: () => void;
}

const NavMenu: React.FC<NavMenuProps> = ({ open, closeMenu }) => {
    const { logout } = useLogout();
    const { refresh } = useAuth();

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
                    <div className="flex items-center space-x-2">
                        <ImEmbed2 className="text-xl text-gray-800" />
                        <p className="font-inter text-gray-800">Get Embeddable Widget</p>
                    </div>
                    <div className="flex items-center space-x-2">
                        <FaNfcSymbol className="text-xl text-gray-800" />
                        <p className="font-inter text-gray-800">Pair NFC device</p>
                    </div>
                    <hr className="border-gray-300" />
                    <p className="text-sm text-gray-600 font-inter">Account</p>
                    <div className="flex items-center space-x-2">
                        <IoPersonSharp className="text-xl text-gray-800" />
                        <p className="font-inter text-gray-800">Manage account</p>
                    </div>
                    <div onClick={onLogout} className="flex items-center space-x-2 cursor-pointer">
                        <IoLogOut className="text-xl text-gray-800" />
                        <p className="font-inter text-gray-800">Logout</p>
                    </div>
                </div>
            </nav>
        </>
    );
};

export default NavMenu;

