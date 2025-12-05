import { MdOutlineMenu, MdModeEdit } from 'react-icons/md';
import { Link } from 'react-router-dom';
import type { CardType } from '@/types/card';

interface TopNavProps {
    openMenu: () => void;
    card: CardType;
    loading: boolean;
}

/**
 * TopNav
 * ------
 * Fixed top navigation bar for the Cardikit dashboard, displaying the
 * current card's name and providing quick access to key actions.
 *
 * Responsibilities:
 * - Render a responsive top bar with:
 *   - A hamburger menu icon (`MdOutlineMenu`) to open the sidebar or menu drawer
 *     on mobile/tablet (`lg:hidden`).
 *   - The title of the currently selected card, centered on large screens.
 *   - An edit button linking to the card editor (`/editor/:id`), hidden on desktop.
 *
 * Loading behavior:
 * - While cards are loading, displays a “Fetching Cards…” placeholder title.
 *
 * UI details:
 * - Uses a translucent background (`bg-gray-300/80`) with backdrop blur.
 * - Ensures long card names fit gracefully using `truncate` with max-width constraints.
 * - Adapts spacing and font sizes across breakpoints (`md`, `lg`).
 *
 * Props:
 * - `openMenu`: Function triggered when the menu icon is tapped.
 * - `card`: The currently selected card whose name will be displayed.
 * - `loading`: When true, hides title details and shows a loading label.
 *
 * @component
 * @since 0.0.2
 */
const TopNav: React.FC<TopNavProps> = ({ openMenu, card, loading }) => {
    return (
        <div className="fixed top-0 w-full z-10 px-4 md:px-8 py-3 md:py-6 lg:px-8 lg:py-4 flex items-center justify-between lg:justify-center text-gray-800 bg-gray-300/80 backdrop-blur">
            <MdOutlineMenu onClick={openMenu} className="text-3xl md:text-4xl cursor-pointer md:w-16 lg:hidden" />
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
