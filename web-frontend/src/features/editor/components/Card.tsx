import { useState } from 'react';
import type { CardType, ItemType } from '@/types/card';
import { getItemConfig } from '@/features/editor/config/itemConfig';
import {
    FaPlus,
    FaEdit,
    FaTrash,
    FaCheck,
    FaTimes,
    FaGripVertical,
} from 'react-icons/fa';
import { useParams } from 'react-router-dom';

interface CardProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    setCard: React.Dispatch<React.SetStateAction<CardType>>;
    loading: boolean;
    onOpenBanner: () => void;
    onOpenAvatar: () => void;
    itemErrors?: Record<string, string>;
    setItemErrors?: React.Dispatch<React.SetStateAction<Record<string, string>>>;
}

const Card: React.FC<CardProps> = ({ card, setOpen, setCard, loading, onOpenBanner, onOpenAvatar, itemErrors = {}, setItemErrors }) => {
    const [editingId, setEditingId] = useState<string | number | null>(null);
    const [editingFields, setEditingFields] = useState<Record<string, string>>({});
    const [draggingId, setDraggingId] = useState<string | null>(null);
    const [dropTargetId, setDropTargetId] = useState<string | null>(null);
    const { id } = useParams();

    const getKey = (item: ItemType): string => String(item.id ?? item.client_id ?? item.position);

    const reorderItems = (sourceKey: string, targetKey: string) => {
        setCard(prev => {
            const items = [...prev.items];
            const fromIndex = items.findIndex(item => getKey(item) === sourceKey);
            const toIndex = items.findIndex(item => getKey(item) === targetKey);

            if (fromIndex === -1 || toIndex === -1 || fromIndex === toIndex) {
                return prev;
            }

            const [moved] = items.splice(fromIndex, 1);

            // If moving down, the removal shifts the target left by 1
            const adjustedToIndex = fromIndex < toIndex ? toIndex - 1 : toIndex;

            items.splice(adjustedToIndex, 0, moved);

            const reindexed = items.map((item, index) => ({
                ...item,
                position: index + 1,
            }));

            return { ...prev, items: reindexed };
        });
    };

    const moveItemToEnd = (sourceKey: string) => {
        setCard(prev => {
            const items = [...prev.items];
            const fromIndex = items.findIndex(item => getKey(item) === sourceKey);
            if (fromIndex === -1) return prev;

            const [moved] = items.splice(fromIndex, 1);
            items.push(moved);

            const reindexed = items.map((item, index) => ({
                ...item,
                position: index + 1,
            }));

            return { ...prev, items: reindexed };
        });
    };

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
        setEditingFields({
            value: item.value ?? '',
            label: item.label ?? '',
        });

        // REMOVE ERROR
        const itemKey = getKey(item);
        if (itemErrors[itemKey] && setItemErrors) {
            setItemErrors(prev => ({ ...prev, [itemKey]: "" }));
        }
    };

    // SAVE EDIT
    const onSave = (item: ItemType) => {
        const itemKey = getKey(item);
        const config = getItemConfig(item.type);
        const includesLabel = config.fields.some(f => f.key === 'label');

        setCard(prev => ({
            ...prev,
            items: prev.items.map(i =>
                getKey(i) === itemKey
                    ? {
                        ...i,
                        value: editingFields.value ?? '',
                        ...(includesLabel ? { label: editingFields.label ?? '' } : { label: undefined }),
                    }
                    : i
            )
        }));

        setEditingId(null);
        setEditingFields({});
    };

    // CANCEL EDIT
    const onCancel = () => {
        setEditingId(null);
        setEditingFields({});
    };

    const accentColor = card.color ?? '#1D4ED8';
    const banner = card.banner_image ?? null;
    const avatar = card.avatar_image ?? null;

    const onDragStart = (e: React.DragEvent, key: string) => {
        setDraggingId(key);
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', String(key));
    };

    const onDragEnd = () => {
        setDraggingId(null);
        setDropTargetId(null);
    };

    const onDropOnItem = (e: React.DragEvent, targetKey: string) => {
        e.preventDefault();
        const sourceKey = draggingId ?? e.dataTransfer.getData('text/plain');
        if (!sourceKey || sourceKey === targetKey) {
            setDraggingId(null);
            setDropTargetId(null);
            return;
        }
        reorderItems(sourceKey, targetKey);
        setDraggingId(null);
        setDropTargetId(null);
    };

    const onDropToEnd = (e: React.DragEvent) => {
        e.preventDefault();
        const sourceKey = draggingId ?? e.dataTransfer.getData('text/plain');
        if (!sourceKey) {
            setDraggingId(null);
            setDropTargetId(null);
            return;
        }
        moveItemToEnd(sourceKey);
        setDraggingId(null);
        setDropTargetId(null);
    };

    return (
        <div className="py-6 flex flex-col items-center w-full lg:w-3/4">
            {loading && id ? (
                <div data-testid="editor-skeleton" className="flex bg-white rounded-xl shadow min-h-[600px] md:min-h-[1000px] w-full md:w-3/4 p-4 flex-col space-y-4 animate-pulse">
                    <div className="h-32 w-full rounded-lg bg-gray-200" />
                    <div className="flex justify-center -mt-10">
                        <div className="w-20 h-20 rounded-full bg-gray-200 border-4 border-white" />
                    </div>
                    {Array.from({ length: 4 }).map((_, idx) => (
                        <div key={idx} className="w-full rounded-lg bg-gray-100 h-16" />
                    ))}
                </div>
            ) : (
                <div className="flex bg-white rounded-xl shadow min-h-[600px] md:min-h-[1000px] w-full md:w-3/4 p-4 flex-col space-y-2">
                    <div className="w-full mb-2">
                        {/* Banner placeholder */}
                        <button
                            type="button"
                            className="w-full h-32 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500"
                            style={{ backgroundColor: banner ? undefined : accentColor + '22' }}
                            onClick={onOpenBanner}
                        >
                            {banner ? (
                                <img src={banner} alt="Card banner" className="w-full h-full object-cover" />
                            ) : (
                                <span className="text-gray-600 font-inter">Add banner</span>
                            )}
                        </button>
                        {/* Avatar placeholder */}
                        <div className="w-full flex justify-center -mt-10">
                            <button
                                type="button"
                                className="w-20 h-20 rounded-full bg-gray-200 border-4 border-white overflow-hidden shadow cursor-pointer flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-primary-500"
                                style={{ backgroundColor: avatar ? undefined : accentColor + '44' }}
                                onClick={onOpenAvatar}
                            >
                                {avatar ? (
                                    <img src={avatar} alt="Card avatar" className="w-full h-full object-cover" />
                                ) : (
                                    <span className="text-gray-500 text-sm font-inter">Avatar</span>
                                )}
                            </button>
                        </div>
                    </div>

                    {card.items.map(item => {
                        const key = getKey(item);
                        const hasError = Boolean(itemErrors[key]);
                        const config = getItemConfig(item.type);
                        const Icon = config.icon;
                        const hasLabelField = config.fields.some(f => f.key === 'label');

                        const itemContainerClasses = [
                            'w-full flex space-x-2 hover:bg-gray-100 rounded-lg p-2 flex-col',
                            hasError ? 'ring-2 ring-red-500 ring-offset-2 ring-offset-white' : '',
                        ].join(' ').trim();

                        const primaryText = hasLabelField ? (item.label || item.value) : item.value;
                        const secondaryText = hasLabelField ? item.value : undefined;

                        return (
                            <div
                                key={key}
                                className="w-full"
                                onDragOver={e => {
                                    if (!draggingId) return;
                                    e.preventDefault();
                                    setDropTargetId(key);
                                }}
                                onDragEnter={e => {
                                    if (!draggingId) return;
                                    e.preventDefault();
                                    setDropTargetId(key);
                                }}
                                onDragLeave={e => {
                                    if (!draggingId) return;
                                    // Only clear if leaving this card entirely
                                    if (!e.currentTarget.contains(e.relatedTarget as Node)) {
                                        setDropTargetId(null);
                                    }
                                }}
                                onDrop={e => onDropOnItem(e, key)}
                            >
                                {dropTargetId === key && (
                                    <div className="h-1 bg-blue-500 rounded-lg mb-2" />
                                )}
                                <div
                                    className={itemContainerClasses}
                                    onClick={() => editingId !== key && onEdit(item)}
                                    draggable
                                    onDragStart={e => onDragStart(e, key)}
                                    onDragEnd={onDragEnd}
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center space-x-2">
                                            <span
                                                className="text-gray-400 cursor-grab active:cursor-grabbing"
                                                aria-hidden
                                            >
                                                <FaGripVertical size={20} />
                                            </span>

                                            {/* Left Icon */}
                                            <div
                                                className="rounded-full p-2"
                                                style={{ backgroundColor: accentColor }}
                                            >
                                                <Icon className={config.iconClass ?? 'text-white'} />
                                            </div>

                                            {/* VALUE OR INPUT */}
                                            <div className="flex items-center">
                                                {editingId === key ? (
                                                    <div className="flex flex-col flex-1 space-y-2">
                                                        {config.fields.map((field, fieldIndex) => (
                                                            <div key={field.key} className="flex flex-col space-y-1">
                                                                <label className="text-xs text-gray-500 font-inter">
                                                                    {field.label}
                                                                </label>
                                                                <input
                                                                    value={editingFields[field.key] ?? ''}
                                                                    onChange={e => setEditingFields(prev => ({
                                                                        ...prev,
                                                                        [field.key]: e.target.value,
                                                                    }))}
                                                                    className="flex-1 p-1 border rounded font-inter w-full"
                                                                    placeholder={field.placeholder ?? 'Value'}
                                                                    autoFocus={fieldIndex === 0}
                                                                />
                                                            </div>
                                                        ))}
                                                    </div>
                                                ) : (
                                                    <div className="flex flex-col flex-1">
                                                        <span className="font-semibold font-inter flex-1">
                                                            {primaryText}
                                                        </span>
                                                        {secondaryText && (
                                                            <span className="text-xs text-gray-500 font-inter break-all">{secondaryText}</span>
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
                        onDragOver={e => draggingId && e.preventDefault()}
                        onDragEnter={e => {
                            if (!draggingId) return;
                            e.preventDefault();
                            setDropTargetId('end');
                        }}
                        onDrop={onDropToEnd}
                        className="w-full flex hover:bg-gray-100 rounded-lg justify-center cursor-pointer p-2 flex-col items-center"
                    >
                        {dropTargetId === 'end' && (
                            <div className="h-1 bg-blue-500 rounded-lg mb-2 w-full" />
                        )}
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
