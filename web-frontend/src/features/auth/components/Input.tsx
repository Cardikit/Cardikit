import { Input as InputCN } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { IoMdEye, IoMdEyeOff } from 'react-icons/io';
import { useState } from 'react';

interface InputProps extends React.ComponentProps<typeof InputCN> {
  startAdornment?: React.ReactNode;
}

const Input = ({
  startAdornment,
  type,
  className,
  ...props
}: InputProps) => {
  const [showPassword, setShowPassword] = useState(false);
  const isPassword = type === 'password';
  const inputType = isPassword && showPassword ? 'text' : type;

  return (
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
        <button
          type="button"
          onClick={() => setShowPassword(prev => !prev)}
          className="absolute right-3 text-muted-foreground focus:outline-none text-2xl cursor-pointer"
        >
          {showPassword ? <IoMdEyeOff className="text-[#FA3C25]" /> : <IoMdEye className="text-[#FA3C25]"/>}
        </button>
      )}
    </div>
  );
};

export default Input;
