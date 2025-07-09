import HeroImage from '@/assets/hero-image.png';
import Logo from '@/assets/logo.png';
import { Link } from 'react-router-dom';

const Welcome: React.FC = () => {
    return (
        <div className="w-full h-screen overflow-hidden bg-[#FBFBFB] flex flex-col items-center justify-center">
            <img src={HeroImage} alt="Welcome Image" className="w-full" />
            <img src={Logo} alt="Cardikit Logo" className="w-36" />
            <h1 className="font-bold text-3xl text-center text-[#1E1E1E]">Your professional identity.<br />One tap away.</h1>
            <Link to="/register" className="bg-[#FC4B4B] text-[#FBFBFB] text-xl w-11/12 py-4 rounded-lg flex items-center justify-center mt-6">Get started</Link>
            <Link to="/login" className="border border-[#1E1E1E] text-[#1E1E1E] text-xl w-11/12 py-4 rounded-lg flex items-center justify-center mt-6">Sign in</Link>
        </div>
    );
}

export default Welcome;


