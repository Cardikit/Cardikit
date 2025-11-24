interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    loading?: boolean;
    children: React.ReactNode;
    className?: string;
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
declare const Button: React.FC<ButtonProps>;
export default Button;
