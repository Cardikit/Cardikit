import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { Link } from 'react-router-dom';
import { FaPlus } from 'react-icons/fa';
const AddCard = () => {
    return (_jsx("div", { className: "p-10", children: _jsxs(Link, { to: "/editor", className: "flex flex-col space-y-4 items-center bg-white rounded-xl shadow h-[600px] cursor-pointer", children: [_jsx("div", { className: "p-4 rounded-full bg-red-100 mt-24", children: _jsx(FaPlus, { className: "text-3xl text-primary-500" }) }), _jsx("span", { className: "text-xl font-semibold text-gray-800 font-inter", children: "Add card" })] }) }));
};
export default AddCard;
