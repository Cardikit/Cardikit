import { MdOutlineMenu, MdModeEdit } from 'react-icons/md';

const TopNav: React.FC = () => {
    return (
        <div className="fixed top-0 w-full z-10 p-4 flex items-center justify-between text-gray-800">
            <MdOutlineMenu className="text-3xl" />
            <h1 className="text-xl font-semibold font-inter">Work</h1>
            <MdModeEdit className="text-2xl" />
        </div>
    );
}

export default TopNav;
