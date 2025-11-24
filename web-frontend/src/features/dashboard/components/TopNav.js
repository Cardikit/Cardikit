import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { MdOutlineMenu, MdModeEdit } from 'react-icons/md';
import { Link } from 'react-router-dom';
const TopNav = ({ openMenu, card, loading }) => {
    return (_jsxs("div", { className: "fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800", children: [_jsx(MdOutlineMenu, { onClick: openMenu, className: "text-3xl cursor-pointer" }), loading ? (_jsx("h1", { className: "text-xl font-semibold font-inter", children: "Fetching Cards..." })) : (_jsx("h1", { className: "text-xl font-semibold font-inter", children: card.name })), _jsx(Link, { to: `/editor/${card.id || ''}`, children: _jsx(MdModeEdit, { className: "text-2xl cursor-pointer" }) })] }));
};
export default TopNav;
