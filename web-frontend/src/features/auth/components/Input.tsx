import { Input as InputCN } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { IoMdEye, IoMdEyeOff } from 'react-icons/io';
import { useState } from 'react';

interface InputProps extends React.ComponentProps<typeof InputCN> {
  startAdornment?: React.ReactNode;
  error?: string;
}

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
const Input = ({
  startAdornment,
  type,
  className,
  error,
  ...props
}: InputProps) => {
  const [showPassword, setShowPassword] = useState(false);
  const isPassword = type === 'password';
  const inputType = isPassword && showPassword ? 'text' : type;

  return (
    <div className="w-full">
        <div className="relative w-full flex items-center">
          {startAdornment && (
            <span className="absolute text-2xl left-3 flex items-center text-muted-foreground pointer-events-none">
              {startAdornment}
            </span>
          )}
          <InputCN
            type={inputType}
            className={cn(
              "rounded-xl text-xl h-14 focus-visible:bg-white bg-[#FBFBFB] focus-visible:shadow-lg focus-visible:ring-0 text-gray-800 font-inter",
              startAdornment && "pl-12",
              isPassword && "pr-12",
              className
            )}
            {...props}
          />
          {isPassword && (
            // @TODO: move away from button due to tabbing accessibility
            <button
              type="button"
              onClick={() => setShowPassword(prev => !prev)}
              className="absolute right-3 text-muted-foreground focus:outline-none text-2xl cursor-pointer"
            >
              {showPassword ? <IoMdEyeOff className="text-[#FA3C25]" /> : <IoMdEye className="text-[#FA3C25]"/>}
            </button>
          )}
        </div>
        {error && <p className="text-[#FA3C25] text-sm mt-2">{error}</p>}
    </div>
  );
};

export default Input;
