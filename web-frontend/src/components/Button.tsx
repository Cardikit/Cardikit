import { cn } from "@/lib/utils";

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    loading?: boolean
    children: React.ReactNode
    className?: string
}

/**
* Button Component
* ----------------
* A reusable button component styled with brand colors and designed for smooth interaction.
*
* Props:
* - `loading`: If true, disables the button and shows a loading text.
* - `children`: The button content (text or element).
* - `className`: Optional additional class names to extend or override styling.
* - Inherits all native button attributes via `ButtonHTMLAttributes`.
*
* Behavior:
* - Shows `"Loading..."` while loading is true
* - Applies hover animation and transition
* - Disabled when loading to prevent duplicate submissions
*
* Styling:
* - Full width, rounded corners, shadow, transition effects
* - Brand colors: #FA3C25 for background, #FBFBFB for text
*
* @since 0.0.1
*/
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
