import { useState } from 'react';
import type { CardType, ItemType } from '@/types/card';
import { FaUser, FaPlus, FaEdit, FaTrash, FaCheck, FaTimes } from 'react-icons/fa';

interface CardProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    setCard: React.Dispatch<React.SetStateAction<CardType>>;
}

const Card: React.FC<CardProps> = ({ card, setOpen, setCard }) => {
    const [editingId, setEditingId] = useState<string | number | null>(null);
    const [editingValue, setEditingValue] = useState("");

    const getKey = (item: ItemType) => item.id ?? item.client_id;

    // DELETE ITEM
    const onDelete = (itemToDelete: ItemType) => {
        const deleteKey = getKey(itemToDelete);

        setCard(prev => {
            // 1. Remove the item
            const filtered = prev.items.filter(item => getKey(item) !== deleteKey);

            // 2. Reindex positions
            const reindexed = filtered.map((item, index) => ({
                ...item,
                position: index + 1, // 1-based positions
            }));

            return {
                ...prev,
                items: reindexed,
            };
        });
    };


    // START EDITING
    const onEdit = (item: ItemType) => {
        setEditingId(getKey(item));
        setEditingValue(item.value);
    };

    // SAVE EDIT
    const onSave = (item: ItemType) => {
        const itemKey = getKey(item);

        setCard(prev => ({
            ...prev,
            items: prev.items.map(i =>
                getKey(i) === itemKey ? { ...i, value: editingValue } : i
            )
        }));

        setEditingId(null);
        setEditingValue("");
    };

    // CANCEL EDIT
    const onCancel = () => {
        setEditingId(null);
        setEditingValue("");
    };

    return (
        <div className="p-10">
            <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col">

                {card.items.map(item => {
                    if (item.type !== 'name') return null;
                    const key = getKey(item);

                    return (
                        <div
                            key={key}
                            className="w-full flex space-x-2 items-center hover:bg-gray-100 rounded-lg p-2"
                        >
                            {/* Left Icon */}
                            <div className="bg-primary-500 rounded-full p-2">
                                <FaUser className="text-white" />
                            </div>

                            {/* VALUE OR INPUT */}
                            <div className="flex-1 flex items-center">
                                {editingId === key ? (
                                    <input
                                        value={editingValue}
                                        onChange={e => setEditingValue(e.target.value)}
                                        className="flex-1 p-1 border rounded font-inter w-full"
                                        autoFocus
                                    />
                                ) : (
                                    <span className="font-semibold font-inter flex-1">
                                        {item.value}
                                    </span>
                                )}
                            </div>

                            {/* ACTION BUTTONS */}
                            <div className="flex space-x-3 items-center">

                                {/* EDIT BUTTON */}
                                {editingId !== key && (
                                    <>
                                        <button
                                            onClick={() => onEdit(item)}
                                            className="text-blue-500 hover:text-blue-600 cursor-pointer"
                                        >
                                            <FaEdit size={16} />
                                        </button>
                                        <button
                                            onClick={() => onDelete(item)}
                                            className="text-red-500 hover:text-red-600 cursor-pointer"
                                        >
                                            <FaTrash size={16} />
                                        </button>
                                    </>
                                )}

                                {/* EDIT MODE ACTIONS */}
                                {editingId === key && (
                                    <>
                                        <button
                                            onClick={() => onSave(item)}
                                            className="text-green-600 hover:text-green-700 cursor-pointer"
                                        >
                                            <FaCheck size={16} />
                                        </button>

                                        <button
                                            onClick={onCancel}
                                            className="text-red-500 hover:text-red-600 cursor-pointer"
                                        >
                                            <FaTimes size={16} />
                                        </button>
                                    </>
                                )}
                            </div>
                        </div>
                    );
                })}

                {/* ADD ITEM */}
                <div
                    onClick={() => setOpen(true)}
                    className="w-full flex hover:bg-gray-100 rounded-lg justify-center cursor-pointer p-2"
                >
                    <div className="p-1 rounded-full bg-red-100">
                        <FaPlus className="text-primary-500" />
                    </div>
                </div>

            </div>
        </div>
    );
};

export default Card;
