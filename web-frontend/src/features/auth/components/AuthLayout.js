import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import Header from '@/assets/header.webp';
import Back from '@/components/Back';
/**
* AuthLayout Component
* --------------------
* This component defines the shared layout used for authentication screens
* such as login and registration.
*
* Features:
* - Includes a `Back` button for navigation.
* - Displays a branded header image centered on the screen.
* - Wraps children in a card-like container with rounded top corners.
* - Responsive and styled for mobile-first display.
*
* Structure:
* - `main`: full height container with gray background.
* - `img`: header banner image (e.g. app logo or illustration).
* - `div`: white content card area where form components (children) are rendered.
*
* Props:
* - `children`: the inner content to be rendered (e.g. form inputs, titles).
*
* @since 0.0.1
*/
const AuthLayout = ({ children }) => {
    return (_jsxs("main", { className: "w-full h-dvh overflow-x-hidden bg-[#E3E3E3] flex flex-col", children: [_jsx(Back, {}), _jsx("img", { className: "w-3/4 mx-auto", src: Header, alt: "Header Image" }), _jsx("div", { className: "w-full bg-[#FBFBFB] rounded-t-4xl flex flex-col items-center flex-grow px-6 py-8 mt-8", children: children })] }));
};
export default AuthLayout;
