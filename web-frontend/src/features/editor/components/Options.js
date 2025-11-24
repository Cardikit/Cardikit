import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { Drawer, DrawerClose, DrawerContent, } from '@/components/ui/drawer';
import { getItemConfig, ITEM_ORDER } from '@/features/editor/config/itemConfig';
const Options = ({ open, setOpen, card, setCard }) => {
    const addItem = (type) => {
        const items = card.items ?? [];
        let topPosition = items.length + 1;
        const config = getItemConfig(type);
        const includesLabel = config.fields.some(f => f.key === 'label');
        const newItem = {
            type,
            value: '',
            position: topPosition,
            client_id: `${Date.now()}-${Math.random().toString(16).slice(2)}`,
            ...(includesLabel ? { label: '' } : {}),
        };
        setCard({
            ...card,
            items: [
                ...items,
                newItem,
            ]
        });
        setOpen(false);
    };
    return (_jsx(Drawer, { open: open, onOpenChange: setOpen, children: _jsxs(DrawerContent, { className: "bg-gray-100 px-6 py-4", children: [_jsx("div", { className: "absolute top-4 left-1/2 -translate-x-1/2 w-1/3 bg-gray-400 h-1 rounded-full cursor-grab" }), _jsxs("div", { className: "w-full", children: [_jsx("div", { className: "w-full flex justify-end", children: _jsx(DrawerClose, { className: "cursor-pointer", children: _jsx("span", { className: "text-gray-800", children: "Done" }) }) }), _jsxs("div", { className: "w-full flex justify-center flex-col", children: [_jsx("span", { className: "text-gray-800 font-semibold font-inter text-center", children: "Select a field below to add it" }), _jsx("div", { className: "w-full grid grid-cols-3 gap-6 mt-6 overflow-y-auto h-72", children: ITEM_ORDER.map(type => {
                                        const config = getItemConfig(type);
                                        const Icon = config.icon;
                                        const fieldLabels = config.fields.map(f => f.label).join(' + ');
                                        return (_jsxs("button", { onClick: () => addItem(type), className: "flex justify-center flex-col items-center hover:bg-gray-200 cursor-pointer p-2 rounded-lg", children: [_jsx("div", { className: `${config.accentClass} rounded-full p-2`, children: _jsx(Icon, { className: config.iconClass ?? 'text-white' }) }), _jsx("span", { className: "text-sm font-inter text-center", children: config.displayName }), _jsx("span", { className: "text-[11px] text-gray-500 font-inter text-center", children: fieldLabels })] }, type));
                                    }) })] })] })] }) }));
};
export default Options;
