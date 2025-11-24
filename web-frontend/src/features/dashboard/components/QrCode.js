import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
const QrCode = ({ currentCard, loading, setOpen }) => {
    const openQrDrawer = () => {
        if (currentCard?.qr_image) {
            setOpen(true);
        }
    };
    return (_jsx("div", { onClick: openQrDrawer, className: "flex-grow flex items-center justify-center cursor-pointer", children: loading ? (_jsx("div", { className: "animate-pulse h-56 w-56 bg-gray-200 rounded-xl" })) : currentCard?.qr_image ? (_jsxs("div", { className: "bg-white rounded-xl shadow p-4 flex flex-col items-center space-y-2", children: [_jsx("img", { src: `${currentCard.qr_image}?t=${Date.now()}`, alt: `QR for ${currentCard.name}`, className: "h-52 w-52 object-contain" }), _jsx("p", { className: "text-sm text-gray-600 font-inter text-center", children: "Scan to view card" })] })) : (_jsxs("div", { className: "bg-white rounded-xl shadow p-4 flex flex-col justify-center items-center space-y-2", children: [_jsx("div", { className: "size-52 flex items-center justify-center", children: _jsx("p", { className: "text-gray-600 font-inter text-center", children: "No QR code available" }) }), _jsx("p", { className: "text-sm text-gray-600 font-inter text-center", children: "Scan to view card" })] })) }));
};
export default QrCode;
