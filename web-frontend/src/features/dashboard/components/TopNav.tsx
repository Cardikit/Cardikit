import { MdOutlineMenu, MdModeEdit } from 'react-icons/md';
import { Link } from 'react-router-dom';
import type { CardType } from '@/types/card';

interface TopNavProps {
    openMenu: () => void;
    card: CardType;
    loading: boolean;
}

const TopNav: React.FC<TopNavProps> = ({ openMenu, card, loading }) => {
    return (
        <div className="fixed top-0 w-full z-10 px-4 md:px-8 py-3 md:py-6 lg:px-8 lg:py-4 flex items-center justify-between text-gray-800 bg-gray-300/80 backdrop-blur">
            <MdOutlineMenu onClick={openMenu} className="text-3xl md:text-4xl cursor-pointer md:w-16" />
            {loading ? (
                <h1 className="text-xl md:text-2xl font-semibold font-inter">Fetching Cards...</h1>
            ) : (
                <h1 className="text-xl md:text-2xl font-semibold font-inter truncate max-w-xs lg:max-w-md text-center">{card.name}</h1>
            )}
            <Link to={`/editor/${card.id || ''}`} className="text-primary-600 hover:text-primary-800 lg:hidden md:w-16">
                <div className="flex items-center justify-center space-x-2">
                    <span className="hidden md:flex text-lg font-semibold">Edit</span>
                    <MdModeEdit className="text-2xl md:text-2xl cursor-pointer" />
                </div>
            </Link>
        </div>
    );
}

export default TopNav;
