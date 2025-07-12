import { MdOutlineMenu, MdModeEdit } from 'react-icons/md';

interface TopNavProps {
    openMenu: () => void;
    card: Card;
}

interface Card {
    id: number;
    name: string;
}

const TopNav: React.FC<TopNavProps> = ({ openMenu, card }) => {
    return (
        <div className="fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800">
            <MdOutlineMenu onClick={openMenu} className="text-3xl cursor-pointer" />
            <h1 className="text-xl font-semibold font-inter">{card.name}</h1>
            <MdModeEdit className="text-2xl" />
        </div>
    );
}

export default TopNav;
