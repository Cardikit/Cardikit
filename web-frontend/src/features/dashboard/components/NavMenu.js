import { jsx as _jsx, jsxs as _jsxs, Fragment as _Fragment } from "react/jsx-runtime";
import Logo from '@/assets/logo.webp';
import { ImEmbed2 } from 'react-icons/im';
import { FaNfcSymbol } from 'react-icons/fa6';
import { IoPersonSharp, IoLogOut, IoClose } from "react-icons/io5";
import { useLogout } from '@/features/auth/hooks/useLogout';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
import { useAuth } from '@/contexts/AuthContext';
const NavMenu = ({ open, closeMenu }) => {
    const { logout } = useLogout();
    const { refresh } = useAuth();
    const onLogout = async () => {
        try {
            await fetchCsrfToken();
            await logout();
            await refresh();
        }
        catch (err) {
            console.log(err);
        }
    };
    return (_jsxs(_Fragment, { children: [open && (_jsx("div", { onClick: closeMenu, className: "fixed top-0 left-0 w-full h-dvh bg-black opacity-40 z-40" })), _jsx("nav", { className: `fixed top-0 left-0 h-dvh w-3/4 bg-gray-100 z-50 shadow-md transition-transform duration-300 ease-in-out ${open ? 'translate-x-0' : '-translate-x-full'}`, children: _jsxs("div", { className: "p-4 flex flex-col space-y-4", children: [_jsxs("div", { className: "flex justify-between items-center pb-6", children: [_jsx("img", { src: Logo, alt: "Logo", className: "w-12" }), _jsx(IoClose, { onClick: closeMenu, className: "text-2xl text-gray-800 cursor-pointer" })] }), _jsx("p", { className: "text-sm text-gray-600 font-inter", children: "Connect Devices" }), _jsxs("div", { className: "flex items-center space-x-2", children: [_jsx(ImEmbed2, { className: "text-xl text-gray-800" }), _jsx("p", { className: "font-inter text-gray-800", children: "Get Embeddable Widget" })] }), _jsxs("div", { className: "flex items-center space-x-2", children: [_jsx(FaNfcSymbol, { className: "text-xl text-gray-800" }), _jsx("p", { className: "font-inter text-gray-800", children: "Pair NFC device" })] }), _jsx("hr", { className: "border-gray-300" }), _jsx("p", { className: "text-sm text-gray-600 font-inter", children: "Account" }), _jsxs("div", { className: "flex items-center space-x-2", children: [_jsx(IoPersonSharp, { className: "text-xl text-gray-800" }), _jsx("p", { className: "font-inter text-gray-800", children: "Manage account" })] }), _jsxs("div", { onClick: onLogout, className: "flex items-center space-x-2 cursor-pointer", children: [_jsx(IoLogOut, { className: "text-xl text-gray-800" }), _jsx("p", { className: "font-inter text-gray-800", children: "Logout" })] })] }) })] }));
};
export default NavMenu;
