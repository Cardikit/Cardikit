import { Input as InputCN } from '@/components/ui/input';
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
declare const Input: ({ startAdornment, type, className, error, ...props }: InputProps) => import("react/jsx-runtime").JSX.Element;
export default Input;
