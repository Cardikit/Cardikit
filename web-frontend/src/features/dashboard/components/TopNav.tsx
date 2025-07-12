import { MdOutlineMenu, MdModeEdit } from 'react-icons/md';

interface TopNavProps {
    openMenu: () => void;
}

const TopNav: React.FC<TopNavProps> = ({ openMenu }) => {
    return (
        <div className="fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800">
            <MdOutlineMenu onClick={openMenu} className="text-3xl cursor-pointer" />
            <h1 className="text-xl font-semibold font-inter">Work</h1>
            <MdModeEdit className="text-2xl" />
        </div>
    );
}

export default TopNav;
