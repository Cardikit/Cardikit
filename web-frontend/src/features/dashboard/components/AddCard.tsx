import { Link } from 'react-router-dom';
import { FaPlus } from 'react-icons/fa';

const AddCard: React.FC = () => {
    return (
        <div className="p-10">
            <Link to="/editor" className="flex flex-col space-y-4 items-center bg-white rounded-xl shadow h-[600px] cursor-pointer">
                <div className="p-4 rounded-full bg-red-100 mt-24">
                    <FaPlus className="text-3xl text-primary-500" />
                </div>
                <span className="text-xl font-semibold text-gray-800 font-inter">Add card</span>
            </Link>
        </div>
    );
}

export default AddCard;
