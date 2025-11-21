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
        <div className="fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800">
            <MdOutlineMenu onClick={openMenu} className="text-3xl cursor-pointer" />
            {loading ? (
                <h1 className="text-xl font-semibold font-inter">Fetching Cards...</h1>
            ) : (
                <h1 className="text-xl font-semibold font-inter">{card.name}</h1>
            )}
            <Link to={`/editor/${card.id || ''}`}>
                <MdModeEdit className="text-2xl cursor-pointer" />
            </Link>
        </div>
    );
}

export default TopNav;
