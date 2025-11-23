import { useState } from 'react';
import type { CardType, ItemType } from '@/types/card';
import {
    FaUser,
    FaPlus,
    FaEdit,
    FaTrash,
    FaCheck,
    FaTimes,
    FaBriefcase,
    FaBuilding,
    FaHeading,
    FaPhone,
    FaEnvelope,
    FaLink,
    FaMapMarkerAlt,
    FaGlobe,
    FaLinkedin,
    FaInstagram,
    FaCalendarAlt,
    FaTwitter,
    FaFacebook,
    FaHashtag,
    FaSnapchatGhost,
    FaMusic,
    FaYoutube,
    FaGithub,
    FaYelp,
    FaPaypal,
    FaMoneyBillWave,
    FaDiscord,
    FaSkype,
    FaTelegramPlane,
    FaTwitch,
    FaWhatsapp,
} from 'react-icons/fa';
import { useParams } from 'react-router-dom';

interface CardProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    setCard: React.Dispatch<React.SetStateAction<CardType>>;
    loading: boolean;
    itemErrors?: Record<string, string>;
    setItemErrors?: React.Dispatch<React.SetStateAction<Record<string, string>>>;
}

const Card: React.FC<CardProps> = ({ card, setOpen, setCard, loading, itemErrors = {}, setItemErrors }) => {
    const [editingId, setEditingId] = useState<string | number | null>(null);
    const [editingValue, setEditingValue] = useState("");
    const [editingLabel, setEditingLabel] = useState<string>("");
    const { id } = useParams();

    const getKey = (item: ItemType) => item.id ?? item.client_id;

    const typeIconMap: Record<string, React.ComponentType<any>> = {
        name: FaUser,
        job_title: FaBriefcase,
        department: FaBuilding,
        company: FaBuilding,
        headline: FaHeading,
        phone: FaPhone,
        email: FaEnvelope,
        link: FaLink,
        address: FaMapMarkerAlt,
        website: FaGlobe,
        linkedin: FaLinkedin,
        instagram: FaInstagram,
        calendly: FaCalendarAlt,
        x: FaTwitter,
        facebook: FaFacebook,
        threads: FaHashtag,
        snapchat: FaSnapchatGhost,
        tiktok: FaMusic,
        youtube: FaYoutube,
        github: FaGithub,
        yelp: FaYelp,
        venmo: FaMoneyBillWave,
        paypal: FaPaypal,
        cashapp: FaMoneyBillWave,
        discord: FaDiscord,
        signal: FaHashtag,
        skype: FaSkype,
        telegram: FaTelegramPlane,
        twitch: FaTwitch,
        whatsapp: FaWhatsapp,
        pronouns: FaHashtag,
        bio: FaHeading,
        portfolio: FaLink,
    };

    const getIconForType = (type: string) => typeIconMap[type] ?? FaLink;

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
        setEditingLabel(item.label ?? '');

        // REMOVE ERROR
        const itemKey = getKey(item);
        if (itemErrors[itemKey] && setItemErrors) {
            setItemErrors(prev => ({ ...prev, [itemKey]: "" }));
        }
    };

    // SAVE EDIT
    const onSave = (item: ItemType) => {
        const itemKey = getKey(item);

        setCard(prev => ({
            ...prev,
            items: prev.items.map(i =>
                getKey(i) === itemKey ? { ...i, value: editingValue, label: editingLabel } : i
            )
        }));

        setEditingId(null);
        setEditingValue("");
        setEditingLabel("");
    };

    // CANCEL EDIT
    const onCancel = () => {
        setEditingId(null);
        setEditingValue("");
        setEditingLabel("");
    };

    return (
        <div className="p-10">
            {loading && id ? <div>Loading...</div> : (
                <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col space-y-2">

                    {card.items.map(item => {
                        const key = getKey(item);
                        const hasError = Boolean(itemErrors[key]);
                        const Icon = getIconForType(item.type);

                        const itemContainerClasses = [
                            'w-full flex space-x-2 hover:bg-gray-100 rounded-lg p-2 flex-col',
                            hasError ? 'ring-2 ring-red-500 ring-offset-2 ring-offset-white' : '',
                        ].join(' ').trim();

                        return (
                            <div key={key} className="w-full">
                                <div
                                    className={itemContainerClasses}
                                    onClick={() => editingId !== key && onEdit(item)}
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-2">
                                            {/* Left Icon */}
                                            <div className="bg-primary-500 rounded-full p-2">
                                                <Icon className="text-white" />
                                            </div>

                                            {/* VALUE OR INPUT */}
                                            <div className="flex items-center">
                                                {editingId === key ? (
                                                    <div className="flex flex-col flex-1 space-y-2">
                                                        <label htmlFor="label" className="text-xs text-gray-500 font-inter">
                                                            Label
                                                        </label>
                                                        <input
                                                            value={editingLabel}
                                                            onChange={e => setEditingLabel(e.target.value)}
                                                            className="flex-1 p-1 border rounded font-inter w-full"
                                                            placeholder="Label (e.g. Company website)"
                                                        />
                                                        <label htmlFor="value" className="text-xs text-gray-500 font-inter mt-2">
                                                            Value
                                                        </label>
                                                        <input
                                                            value={editingValue}
                                                            onChange={e => setEditingValue(e.target.value)}
                                                            className="flex-1 p-1 border rounded font-inter w-full"
                                                            placeholder="Value"
                                                            autoFocus
                                                        />
                                                    </div>
                                                ) : (
                                                    <div className="flex flex-col flex-1">
                                                        <span className="font-semibold font-inter flex-1">
                                                            {item.value}
                                                        </span>
                                                        {item.label && (
                                                            <span className="text-xs text-gray-500 font-inter">{item.label}</span>
                                                        )}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                        {/* EDIT BUTTON */}
                                        {editingId !== key && (
                                            <button
                                                className="text-blue-500 hover:text-blue-600 cursor-pointer"
                                            >
                                                <FaEdit size={20} />
                                            </button>
                                        )}
                                    </div>

                                    {/* EDIT MODE ACTIONS */}
                                    {editingId === key && (
                                        <div className="flex items-center justify-between my-6">
                                            <button
                                                onClick={() => onSave(item)}
                                                className="text-white bg-green-600 hover:bg-green-700 cursor-pointer p-2 rounded-lg"
                                            >
                                                <FaCheck size={24} />
                                            </button>

                                            <button
                                                onClick={onCancel}
                                                className="bg-red-500 text-white hover:bg-red-600 cursor-pointer p-2 rounded-lg"
                                            >
                                                <FaTimes size={24} />
                                            </button>

                                            <button
                                                onClick={() => onDelete(item)}
                                                className="bg-red-500 text-white hover:bg-red-600 cursor-pointer p-2 rounded-lg"
                                            >
                                                <FaTrash size={24} />
                                            </button>
                                        </div>
                                    )}
                                </div>
                                {hasError && (
                                    <p className="text-red-600 text-sm mt-1 ml-12 font-inter">{itemErrors[key]}</p>
                                )}
                            </div>
                        );
                    })}

                    {/* ADD ITEM */}
                    <div
                        onClick={() => setOpen(true)}
                        className="w-full flex hover:bg-gray-100 rounded-lg justify-center cursor-pointer p-2"
                    >
                        <div className="p-2 rounded-full bg-red-100">
                            <FaPlus className="text-xl text-primary-500" />
                        </div>
                    </div>

                </div>
            )}
        </div>
    );
};

export default Card;
