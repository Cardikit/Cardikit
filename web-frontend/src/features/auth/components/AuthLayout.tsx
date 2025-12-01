import Header from '@/assets/header.webp';
import Back from '@/components/Back';
import type { ReactNode } from 'react';

interface AuthLayoutProps {
    children: ReactNode
}

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
const AuthLayout: React.FC<AuthLayoutProps> = ({ children }) => {
    return (
        <main className="min-h-dvh overflow-x-hidden bg-[#E3E3E3] flex flex-col">
            <div className="px-4 pt-6 sm:px-6 lg:px-10 lg:pt-8">
                <Back />
            </div>
            <img className="w-3/4 max-w-md mx-auto mt-4 sm:mt-6 lg:mt-8" src={Header} alt="Header Image" />
            <div className="w-full bg-[#FBFBFB] rounded-t-4xl flex flex-col items-center flex-grow px-6 py-8 mt-8 shadow-lg">
                <div className="w-full max-w-lg">{children}</div>
            </div>
        </main>
    );
}

export default AuthLayout;
