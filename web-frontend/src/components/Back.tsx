import { IoMdArrowBack } from 'react-icons/io';
import { useNavigate } from 'react-router-dom';

const Back = () => {
    const navigate = useNavigate();

    const handleBack = () => {
        navigate(-1);
    };

    return (
        <div
            className="absolute top-4 left-4 text-[#1E1E1E] bg-[#FBFBFB] p-2 rounded-full shadow-md cursor-pointer"
            onClick={handleBack}
        >
            <IoMdArrowBack className="text-2xl" />
        </div>
    );
}

export default Back;
