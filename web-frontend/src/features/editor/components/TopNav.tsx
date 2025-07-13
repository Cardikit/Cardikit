import { MdModeEdit } from 'react-icons/md';
import { Link } from 'react-router-dom';
import type { Card } from '@/types/card';

interface TopNavProps {
    card: Card;
    setOpen: (open: boolean) => void;
}

const TopNav: React.FC<TopNavProps> = ({ card, setOpen }) => {

    return (
        <div className="fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800">
            <Link to="/dashboard" className="font-inter cursor-pointer">Cancel</Link>
            <div onClick={() => setOpen(true)} className="flex items-center space-x-2 cursor-pointer">
                <h1 className="text-xl font-semibold font-inter">{card.name}</h1>
                <MdModeEdit className="text-2xl" />
            </div>
            <p className="font-inter cursor-pointer">Save</p>
        </div>
    );
}

export default TopNav;
