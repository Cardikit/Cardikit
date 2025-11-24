import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { FaAddressCard, FaUserFriends } from 'react-icons/fa';
import { Link, useLocation } from 'react-router-dom';
const BottomNav = () => {
    const location = useLocation();
    const isActive = (path) => location.pathname === path;
    return (_jsxs("div", { className: "fixed bottom-0 w-full bg-background-100 shadow-md z-10 flex justify-around items-center py-4 border-t border-gray-200", children: [_jsxs(Link, { to: "/dashboard", className: `flex flex-col items-center ${isActive('/dashboard') ? 'text-primary-500 cursor-default' : 'text-gray-800'}`, children: [_jsx(FaAddressCard, { className: "text-3xl" }), _jsx("span", { className: "text-xs font-inter", children: "My Cards" })] }), _jsxs(Link, { to: "/dashboard/contacts", className: `flex flex-col items-center ${isActive('/dashboard/contacts') ? 'text-primary-500 cursor-default' : 'text-gray-500'}`, children: [_jsx(FaUserFriends, { className: "text-3xl" }), _jsx("span", { className: "text-xs font-inter", children: "Contacts" })] })] }));
};
export default BottomNav;
