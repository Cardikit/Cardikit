import type { CardType } from '@/types/card';
import { FaUser } from 'react-icons/fa';

interface CardProps {
    card: CardType
}

const Card: React.FC<CardProps> = ({ card }) => {

    return (
        <div className="p-10">
            <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col">
                {card.items.map((item, index) => (
                    item.type === 'name' && (
                        <div key={index} className="w-full flex space-x-2 items-center">
                            <div className="bg-primary-500 rounded-full p-2">
                                <FaUser className="text-white" />
                            </div>
                            <span className="font-semibold font-inter">{item.value}</span>
                        </div>
                    )
                ))}
            </div>
        </div>
    );
}

export default Card;
