import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { Input as InputCN } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { IoMdEye, IoMdEyeOff } from 'react-icons/io';
import { useState } from 'react';
/**
* Custom Input Component
* ----------------------
* A reusable input component built on top of the base UI `InputCN`.
*
* Features:
* - Optional icon before the input (`startAdornment`)
* - Error display below the input
* - Password visibility toggle for password inputs
* - Tailwind-based styling for consistent UI
*
* Props:
* - `startAdornment`: optional React node shown on the left inside the input (e.g. icon)
* - `error`: optional error string to display below the input
* - All native Input props are supported via spread operator
*
* @since 0.0.1
*/
const Input = ({ startAdornment, type, className, error, ...props }) => {
    const [showPassword, setShowPassword] = useState(false);
    const isPassword = type === 'password';
    const inputType = isPassword && showPassword ? 'text' : type;
    return (_jsxs("div", { className: "w-full", children: [_jsxs("div", { className: "relative w-full flex items-center", children: [startAdornment && (_jsx("span", { className: "absolute text-2xl left-3 flex items-center text-muted-foreground pointer-events-none", children: startAdornment })), _jsx(InputCN, { type: inputType ?? 'text', className: cn("rounded-xl text-xl h-14 focus-visible:bg-white bg-[#FBFBFB] focus-visible:shadow-lg focus-visible:ring-0 text-gray-800 font-inter", startAdornment && "pl-12", isPassword && "pr-12", className), ...props }), isPassword && (
                    // @TODO: move away from button due to tabbing accessibility
                    _jsx("button", { type: "button", onClick: () => setShowPassword(prev => !prev), className: "absolute right-3 text-muted-foreground focus:outline-none text-2xl cursor-pointer", children: showPassword ? _jsx(IoMdEyeOff, { className: "text-[#FA3C25]", "data-testid": "eye-off-icon" }) : _jsx(IoMdEye, { className: "text-[#FA3C25]", "data-testid": "eye-on-icon" }) }))] }), error && _jsx("p", { className: "text-[#FA3C25] text-sm mt-2", children: error })] }));
};
export default Input;
