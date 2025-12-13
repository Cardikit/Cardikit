import { Link } from 'react-router-dom';
import { FaPlus } from 'react-icons/fa';

interface AddCardProps {
    onCreateCard?: () => void;
}

/**
 * AddCard
 * -------
 * Simple UI entry point for creating a new Cardikit card.
 *
 * Responsibilities:
 * - Renders a stylized card with a plus icon.
 * - Wraps the entire element in a <Link> that navigates to `/editor`,
 *   where the user can create a new digital business card.
 * - Provides consistent spacing and layout for mobile and desktop.
 *
 * Visual notes:
 * - Large centered plus icon inside a red-tinted circle.
 * - Uses a tall, clickable container to match the visual style
 *   of other card tiles in the dashboard.
 *
 * @component
 * @since 0.0.2
 */
const AddCard: React.FC<AddCardProps> = ({ onCreateCard }) => {
    const handleClick: React.MouseEventHandler<HTMLAnchorElement> = (event) => {
        if (!onCreateCard) return;
        event.preventDefault();
        onCreateCard();
    };

    return (
        <div className="p-10 flex flex-col items-center">
            <Link
                to="/editor"
                onClick={handleClick}
                className="flex flex-col space-y-4 items-center bg-white rounded-xl w-full md:w-3/4 lg:w-1/2 shadow h-[600px] cursor-pointer"
            >
                <div className="p-4 rounded-full bg-red-100 mt-24">
                    <FaPlus className="text-3xl text-primary-500" />
                </div>
                <span className="text-xl font-semibold text-gray-800 font-inter">Add card</span>
            </Link>
        </div>
    );
}

export default AddCard;
