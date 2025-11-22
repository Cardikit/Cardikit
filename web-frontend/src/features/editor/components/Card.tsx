import type { CardType } from '@/types/card';
import { FaUser, FaPlus } from 'react-icons/fa';

interface CardProps {
    card: CardType;
    setOpen: (open: boolean) => void
}

const Card: React.FC<CardProps> = ({ card, setOpen }) => {
    return (
        <div className="p-10">
            <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col">
                {card.items && (
                    card.items.map((item, index) => (
                        item.type === 'name' && (
                            <div key={index} className="w-full flex space-x-2 items-center">
                                <div className="bg-primary-500 rounded-full p-2">
                                    <FaUser className="text-white" />
                                </div>
                                <span className="font-semibold font-inter">{item.value}</span>
                            </div>
                        )
                    ))
                )}
                <div onClick={() => setOpen(true)} className="w-full flex hover:bg-gray-100 rounded-lg justify-center cursor-pointer p-2">
                    <div className="p-1 rounded-full bg-red-100">
                        <FaPlus className="text-primary-500" />
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Card;
