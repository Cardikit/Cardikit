import type { CardType } from '@/types/card';
import type { ItemType } from '@/types/card';
import { FaUser, FaPlus, FaEdit, FaTrash } from 'react-icons/fa';

interface CardProps {
    card: CardType;
    setOpen: (open: boolean) => void
    setCard: (card: CardType) => void
}

const Card: React.FC<CardProps> = ({ card, setOpen, setCard }) => {

    const getKey = (item: ItemType) => item.id ?? item.client_id;

    const onDelete = (itemToDelete: ItemType) => {
        const deleteKey = getKey(itemToDelete);

        setCard({
            ...card,
            items: card.items.filter(item => getKey(item) !== deleteKey)
        });
    }

    return (
        <div className="p-10">
            <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col">
                {card.items && (
                    card.items.map((item) => (
                        item.type === 'name' && (
                            <div
                                key={getKey(item)}
                                className="group w-full flex space-x-2 items-center hover:bg-gray-100 rounded-lg cursor-pointer p-2"
                            >
                                {/* Left Icon */}
                                <div className="bg-primary-500 rounded-full p-2">
                                    <FaUser className="text-white" />
                                </div>

                                {/* Value */}
                                <span className="font-semibold font-inter flex-1">
                                    {item.value}
                                </span>

                                {/* Hover Actions */}
                                <div className="flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button
                                        className="text-gray-500 hover:text-blue-600 cursor-pointer"
                                    >
                                        <FaEdit size={16} />
                                    </button>
                                    <button
                                        onClick={() => onDelete(item)}
                                        className="text-gray-500 hover:text-red-600 cursor-pointer"
                                    >
                                        <FaTrash size={16} />
                                    </button>
                                </div>
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
