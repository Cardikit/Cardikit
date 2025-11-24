import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { Drawer, DrawerContent, DrawerClose, } from '@/components/ui/drawer';
import { useRef, useState } from 'react';
const ImageUploadModal = ({ open, onClose, onSave, title, allowClear = true }) => {
    const inputRef = useRef(null);
    const [error, setError] = useState(null);
    const onFileChange = async (e) => {
        const file = e.target.files?.[0];
        if (!file)
            return;
        if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type)) {
            setError('Only PNG, JPG, and WEBP are allowed');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            setError('Max size is 5MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = () => {
            const result = reader.result;
            onSave(result);
            onClose();
        };
        reader.readAsDataURL(file);
    };
    const onRemove = () => {
        onSave(null);
        onClose();
    };
    return (_jsx(Drawer, { open: open, onOpenChange: (o) => !o && onClose(), children: _jsxs(DrawerContent, { className: "bg-gray-100 px-6 py-4", children: [_jsx("div", { className: "absolute top-4 left-1/2 -translate-x-1/2 w-1/3 bg-gray-400 h-1 rounded-full cursor-grab" }), _jsxs("div", { className: "w-full", children: [_jsx("div", { className: "w-full flex justify-end", children: _jsx(DrawerClose, { className: "cursor-pointer", children: _jsx("span", { className: "text-gray-800", children: "Done" }) }) }), _jsxs("div", { className: "space-y-4", children: [_jsx("h2", { className: "text-lg font-semibold font-inter text-gray-800", children: title }), _jsx("input", { ref: inputRef, type: "file", accept: "image/png,image/jpeg,image/webp", className: "hidden", onChange: onFileChange }), _jsx("button", { type: "button", onClick: () => inputRef.current?.click(), className: "w-full bg-white border border-gray-300 rounded-lg py-3 text-center cursor-pointer hover:bg-gray-50 font-inter", children: "Upload image" }), allowClear && (_jsx("button", { type: "button", onClick: onRemove, className: "w-full bg-white border border-red-400 text-red-600 rounded-lg py-3 text-center cursor-pointer hover:bg-red-50 font-inter", children: "Remove image" })), error && _jsx("p", { className: "text-red-600 text-sm font-inter", children: error })] })] })] }) }));
};
export default ImageUploadModal;
