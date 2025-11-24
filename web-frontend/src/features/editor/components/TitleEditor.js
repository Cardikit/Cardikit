import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { Drawer, DrawerClose, DrawerContent, } from '@/components/ui/drawer';
import Input from '@/features/auth/components/Input';
import { FaIdCard } from 'react-icons/fa';
const TitleEditor = ({ open, setOpen, card, setCard }) => {
    return (_jsx(Drawer, { open: open, onOpenChange: setOpen, children: _jsxs(DrawerContent, { className: "bg-gray-100 px-6 py-4", children: [_jsx("div", { className: "absolute top-4 left-1/2 -translate-x-1/2 w-1/3 bg-gray-400 h-1 rounded-full cursor-grab" }), _jsxs("div", { className: "w-full", children: [_jsx("div", { className: "w-full flex justify-end", children: _jsx(DrawerClose, { className: "cursor-pointer", children: _jsx("span", { className: "text-gray-800", children: "Done" }) }) }), _jsx("label", { htmlFor: "name", className: "block mb-2 text-sm font-medium text-gray-800", children: "Edit card name" }), _jsx(Input, { id: "name", type: "text", placeholder: "Card name", className: "w-full", value: card.name, onChange: (e) => setCard({ ...card, name: e.target.value }), startAdornment: _jsx(FaIdCard, { className: "text-primary-500" }), autoFocus: true, onKeyDown: (e) => {
                                if (e.key === 'Enter') {
                                    e.preventDefault();
                                    setOpen(false);
                                }
                            } })] })] }) }));
};
export default TitleEditor;
