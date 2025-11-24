import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { getItemConfig } from '@/features/dashboard/config/itemConfig';
const Card = ({ card }) => {
    const accentColor = card.color ?? '#1D4ED8';
    const banner = card.banner_image ?? null;
    const avatar = card.avatar_image ?? null;
    return (_jsx("div", { className: "p-10", children: _jsxs("div", { className: "flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col space-y-3", children: [_jsxs("div", { className: "w-full mb-2", children: [_jsx("div", { className: "w-full h-32 rounded-lg bg-gray-100 overflow-hidden", style: { backgroundColor: banner ? undefined : accentColor + '22' }, children: banner && _jsx("img", { src: banner, alt: "Card banner", className: "w-full h-full object-cover" }) }), _jsx("div", { className: "w-full flex justify-center -mt-10", children: _jsx("div", { className: "w-20 h-20 rounded-full bg-gray-200 border-4 border-white overflow-hidden shadow flex items-center justify-center", style: { backgroundColor: avatar ? undefined : accentColor + '44' }, children: avatar ? (_jsx("img", { src: avatar, alt: "Card avatar", className: "w-full h-full object-cover" })) : (_jsx("span", { className: "text-gray-500 text-sm font-inter", children: "Avatar" })) }) })] }), card.items?.map((item, index) => {
                    const config = getItemConfig(item.type);
                    const Icon = config.icon;
                    const hasLabel = config.fields.label && item.label;
                    const primaryText = hasLabel ? item.label : item.value;
                    const secondaryText = hasLabel ? item.value : undefined;
                    const key = item.id ?? item.client_id ?? index;
                    const iconColorClass = config.iconClass ?? 'text-white';
                    const content = (_jsxs("div", { className: "flex items-start space-x-3", children: [_jsx("div", { className: "rounded-full p-2 flex items-center justify-center", style: { backgroundColor: accentColor }, children: _jsx(Icon, { className: iconColorClass }) }), _jsxs("div", { className: "flex flex-col", children: [_jsx("span", { className: "font-semibold font-inter text-lg leading-tight break-all", children: primaryText }), secondaryText && (_jsx("span", { className: "text-sm text-gray-600 font-inter break-all", children: secondaryText }))] })] }));
                    if (config.fields.link) {
                        return (_jsx("a", { href: item.value, target: "_blank", rel: "noopener noreferrer", className: "w-full rounded-lg hover:bg-gray-100 transition-colors p-2 block", children: content }, key));
                    }
                    return (_jsx("div", { className: "w-full rounded-lg p-2", children: content }, key));
                })] }) }));
};
export default Card;
