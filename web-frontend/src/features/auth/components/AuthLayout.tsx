import Header from '@/assets/header.webp';
import Back from '@/components/Back';
import type { ReactNode } from 'react';

interface AuthLayoutProps {
    children: ReactNode
}

const AuthLayout: React.FC<AuthLayoutProps> = ({ children }) => {
    return (
        <main className="w-full h-dvh overflow-x-hidden bg-[#E3E3E3] flex flex-col">
            <Back />
            <img className="w-3/4 mx-auto" src={Header} alt="Header Image" />
            <div className="w-full bg-[#FBFBFB] rounded-t-4xl flex flex-col items-center flex-grow px-6 py-8 mt-8">
                {children}
            </div>
            </main>
    );
}

export default AuthLayout;
