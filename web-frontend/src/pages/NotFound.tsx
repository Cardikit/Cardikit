import Header from '@/assets/header.webp';
import { Link } from 'react-router-dom';

const NotFound: React.FC = () => {
    return (
        <main className="min-h-dvh bg-[#E3E3E3] flex flex-col">
            <div className="flex flex-col items-center px-6 pt-10 pb-12 flex-1">
                <img src={Header} alt="Cardikit" className="w-3/4 max-w-sm mx-auto" />

                <div className="w-full bg-[#FBFBFB] rounded-t-4xl flex flex-col items-center flex-1 px-6 py-10 mt-8 shadow-lg">
                    <div className="w-full max-w-md text-center space-y-4">
                        <p className="text-sm uppercase tracking-[0.2em] text-gray-400 font-semibold">404</p>
                        <h1 className="text-2xl md:text-3xl font-bold text-gray-900 font-inter">Page not found</h1>
                        <p className="text-gray-600 font-inter">
                            The page you’re looking for doesn’t exist or was moved. Let’s get you back to your cards.
                        </p>
                    </div>

                    <div className="w-full max-w-md mt-10 space-y-4">
                        <Link
                            to="/"
                            className="block w-full bg-primary-500 text-gray-100 py-3 rounded-xl font-semibold text-center shadow hover:bg-primary-900 transition-colors"
                        >
                            Go to Dashboard
                        </Link>
                        <Link
                            to="/welcome"
                            className="block w-full border border-gray-300 text-gray-800 py-3 rounded-xl font-semibold text-center hover:bg-gray-100 transition-colors"
                        >
                            Back to Home
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    );
};

export default NotFound;
