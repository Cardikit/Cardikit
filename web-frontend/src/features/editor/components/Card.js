import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { useState } from 'react';
import { getItemConfig } from '@/features/editor/config/itemConfig';
import { FaPlus, FaEdit, FaTrash, FaCheck, FaTimes, FaGripVertical, } from 'react-icons/fa';
import { useParams } from 'react-router-dom';
const Card = ({ card, setOpen, setCard, loading, onOpenBanner, onOpenAvatar, itemErrors = {}, setItemErrors }) => {
    const [editingId, setEditingId] = useState(null);
    const [editingFields, setEditingFields] = useState({});
    const [draggingId, setDraggingId] = useState(null);
    const [dropTargetId, setDropTargetId] = useState(null);
    const { id } = useParams();
    const getKey = (item) => String(item.id ?? item.client_id ?? item.position);
    const reorderItems = (sourceKey, targetKey) => {
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
    const moveItemToEnd = (sourceKey) => {
        setCard(prev => {
            const items = [...prev.items];
            const fromIndex = items.findIndex(item => getKey(item) === sourceKey);
            if (fromIndex === -1)
                return prev;
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
    const onDelete = (itemToDelete) => {
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
    const onEdit = (item) => {
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
    const onSave = (item) => {
        const itemKey = getKey(item);
        const config = getItemConfig(item.type);
        const includesLabel = config.fields.some(f => f.key === 'label');
        setCard(prev => ({
            ...prev,
            items: prev.items.map(i => getKey(i) === itemKey
                ? {
                    ...i,
                    value: editingFields.value ?? '',
                    ...(includesLabel ? { label: editingFields.label ?? '' } : { label: undefined }),
                }
                : i)
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
    const onDragStart = (e, key) => {
        setDraggingId(key);
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', String(key));
    };
    const onDragEnd = () => {
        setDraggingId(null);
        setDropTargetId(null);
    };
    const onDropOnItem = (e, targetKey) => {
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
    const onDropToEnd = (e) => {
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
    return (_jsx("div", { className: "p-10", children: loading && id ? _jsx("div", { children: "Loading..." }) : (_jsxs("div", { className: "flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col space-y-2", children: [_jsxs("div", { className: "w-full mb-2", children: [_jsx("button", { type: "button", className: "w-full h-32 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500", style: { backgroundColor: banner ? undefined : accentColor + '22' }, onClick: onOpenBanner, children: banner ? (_jsx("img", { src: banner, alt: "Card banner", className: "w-full h-full object-cover" })) : (_jsx("span", { className: "text-gray-600 font-inter", children: "Add banner" })) }), _jsx("div", { className: "w-full flex justify-center -mt-10", children: _jsx("button", { type: "button", className: "w-20 h-20 rounded-full bg-gray-200 border-4 border-white overflow-hidden shadow cursor-pointer flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-primary-500", style: { backgroundColor: avatar ? undefined : accentColor + '44' }, onClick: onOpenAvatar, children: avatar ? (_jsx("img", { src: avatar, alt: "Card avatar", className: "w-full h-full object-cover" })) : (_jsx("span", { className: "text-gray-500 text-sm font-inter", children: "Avatar" })) }) })] }), card.items.map(item => {
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
                    return (_jsxs("div", { className: "w-full", onDragOver: e => {
                            if (!draggingId)
                                return;
                            e.preventDefault();
                            setDropTargetId(key);
                        }, onDragEnter: e => {
                            if (!draggingId)
                                return;
                            e.preventDefault();
                            setDropTargetId(key);
                        }, onDragLeave: e => {
                            if (!draggingId)
                                return;
                            // Only clear if leaving this card entirely
                            if (!e.currentTarget.contains(e.relatedTarget)) {
                                setDropTargetId(null);
                            }
                        }, onDrop: e => onDropOnItem(e, key), children: [dropTargetId === key && (_jsx("div", { className: "h-1 bg-blue-500 rounded-lg mb-2" })), _jsxs("div", { className: itemContainerClasses, onClick: () => editingId !== key && onEdit(item), draggable: true, onDragStart: e => onDragStart(e, key), onDragEnd: onDragEnd, children: [_jsxs("div", { className: "flex items-center justify-between", children: [_jsxs("div", { className: "flex items-center space-x-2", children: [_jsx("span", { className: "text-gray-400 cursor-grab active:cursor-grabbing", "aria-hidden": true, children: _jsx(FaGripVertical, { size: 20 }) }), _jsx("div", { className: "rounded-full p-2", style: { backgroundColor: accentColor }, children: _jsx(Icon, { className: config.iconClass ?? 'text-white' }) }), _jsx("div", { className: "flex items-center", children: editingId === key ? (_jsx("div", { className: "flex flex-col flex-1 space-y-2", children: config.fields.map((field, fieldIndex) => (_jsxs("div", { className: "flex flex-col space-y-1", children: [_jsx("label", { className: "text-xs text-gray-500 font-inter", children: field.label }), _jsx("input", { value: editingFields[field.key] ?? '', onChange: e => setEditingFields(prev => ({
                                                                            ...prev,
                                                                            [field.key]: e.target.value,
                                                                        })), className: "flex-1 p-1 border rounded font-inter w-full", placeholder: field.placeholder ?? 'Value', autoFocus: fieldIndex === 0 })] }, field.key))) })) : (_jsxs("div", { className: "flex flex-col flex-1", children: [_jsx("span", { className: "font-semibold font-inter flex-1", children: primaryText }), secondaryText && (_jsx("span", { className: "text-xs text-gray-500 font-inter break-all", children: secondaryText }))] })) })] }), editingId !== key && (_jsx("button", { className: "text-blue-500 hover:text-blue-600 cursor-pointer", children: _jsx(FaEdit, { size: 20 }) }))] }), editingId === key && (_jsxs("div", { className: "flex items-center justify-between my-6", children: [_jsx("button", { onClick: () => onSave(item), className: "text-white bg-green-600 hover:bg-green-700 cursor-pointer p-2 rounded-lg", children: _jsx(FaCheck, { size: 24 }) }), _jsx("button", { onClick: onCancel, className: "bg-red-500 text-white hover:bg-red-600 cursor-pointer p-2 rounded-lg", children: _jsx(FaTimes, { size: 24 }) }), _jsx("button", { onClick: () => onDelete(item), className: "bg-red-500 text-white hover:bg-red-600 cursor-pointer p-2 rounded-lg", children: _jsx(FaTrash, { size: 24 }) })] }))] }), hasError && (_jsx("p", { className: "text-red-600 text-sm mt-1 ml-12 font-inter", children: itemErrors[key] }))] }, key));
                }), _jsxs("div", { onClick: () => setOpen(true), onDragOver: e => draggingId && e.preventDefault(), onDragEnter: e => {
                        if (!draggingId)
                            return;
                        e.preventDefault();
                        setDropTargetId('end');
                    }, onDrop: onDropToEnd, className: "w-full flex hover:bg-gray-100 rounded-lg justify-center cursor-pointer p-2 flex-col items-center", children: [dropTargetId === 'end' && (_jsx("div", { className: "h-1 bg-blue-500 rounded-lg mb-2 w-full" })), _jsx("div", { className: "p-2 rounded-full bg-red-100", children: _jsx(FaPlus, { className: "text-xl text-primary-500" }) })] })] })) }));
};
export default Card;
