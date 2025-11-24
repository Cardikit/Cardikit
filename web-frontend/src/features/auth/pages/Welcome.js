import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import HeroImage from '@/assets/hero-image.webp';
import { Link } from 'react-router-dom';
/**
* Welcome Screen
* --------------
*  This is the initial onboarding screen for the Cardikit app.
*  It displays a hero illustration, tagline, and two primary actions:
*  - "Get started": navigates to the registration page
*  - "Sign in": navigates to the login page
*
*  UI is designed to be minimal, mobile-first, and visually engaging.
*
*  @since 0.0.1
*/
const Welcome = () => {
    return (_jsxs("div", { className: "w-full h-dvh overflow-x-hidden bg-background-100 flex flex-col items-center justify-center px-4", children: [_jsx("img", { src: HeroImage, alt: "Welcome Image", className: "w-3/4" }), _jsxs("h1", { className: "font-bold font-inter leading-snug tracking-tight text-2xl text-center text-gray-800 mt-6", children: ["Your professional identity.", _jsx("br", {}), "One tap away."] }), _jsx(Link, { to: "/register", className: "bg-primary-500 border border-primary-500 font-inter hover:bg-primary-900 hover:-translate-y-1 transition-all ease-in-out shadow-md duration-200 text-background-100 text-xl w-full py-4 rounded-lg flex items-center justify-center mt-6", children: "Get started" }), _jsx(Link, { to: "/login", className: "border border-gray-500 bg-background-100 hover:bg-background-200 hover:-translate-y-1 transition-all ease-in-out font-inter text-gray-800 text-xl w-full py-4 shadow-md rounded-lg flex items-center justify-center mt-6", children: "Sign in" })] }));
};
export default Welcome;
