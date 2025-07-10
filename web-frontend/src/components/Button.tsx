import { cn } from "@/lib/utils";

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    loading?: boolean
    children: React.ReactNode
    className?: string
}

const Button: React.FC<ButtonProps> = ({ loading, children, className, ...props }) => {
    return (
        <button
            type="submit"
            className={cn(
                "cursor-pointer bg-[#FA3C25] border border-[#FA3C25] font-inter hover:bg-[#c92f1c] hover:-translate-y-1 transition-all ease-in-out shadow-md duration-200 text-[#FBFBFB] text-xl w-full py-3 rounded-lg flex items-center justify-center",
                className
            )}
            disabled={loading}
            {...props}
        >
            {loading ? 'Loading...' : children}
        </button>
    );
}

export default Button;
