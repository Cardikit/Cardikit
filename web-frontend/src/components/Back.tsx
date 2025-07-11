import { IoMdArrowBack } from 'react-icons/io';
import { useNavigate } from 'react-router-dom';

/**
* Back Button Component
* ---------------------
* Renders a floating circular back button that allows users to navigate
* to the previous page in the browser history.
*
* Useful for mobile or modal-based navigation where users expect a quick return option.
*
* Styling:
* - Positioned absolutely in the top-left corner
* - Styled with a subtle shadow, white background, and dark icon
* - Designed to match clean mobile UI principles
*
* @since 0.0.1
*/
const Back = () => {
    const navigate = useNavigate();

    /**
    * Handles back navigation by going one step back in history.
    *
    * @returns void
    */
    const handleBack = () => {
        navigate(-1);
    };

    return (
        <button
            className="absolute top-4 left-4 text-[#1E1E1E] bg-[#FBFBFB] p-2 rounded-full shadow-md cursor-pointer"
            onClick={handleBack}
        >
            <IoMdArrowBack className="text-2xl" />
        </button>
    );
}

export default Back;
