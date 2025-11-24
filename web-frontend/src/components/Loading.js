import { jsx as _jsx } from "react/jsx-runtime";
/**
* Temporary loading component
*
* @TODO: Replace with actual loading component
* @since 0.0.1
*/
const Loading = () => {
    return (_jsx("div", { className: "flex justify-center items-center h-screen", children: _jsx("p", { className: "text-lg font-semibold", children: "Loading..." }) }));
};
export default Loading;
