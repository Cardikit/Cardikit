import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { useEffect, useState } from 'react';
import TopNav from '@/features/editor/components/TopNav';
import TitleEditor from '@/features/editor/components/TitleEditor';
import Card from '@/features/editor/components/Card';
import Options from '@/features/editor/components/Options';
import ColorPicker from '@/features/editor/components/ColorPicker';
import ImageUploadModal from '@/features/editor/components/ImageUploadModal';
import { useParams, useNavigate } from 'react-router-dom';
import { useFetchCard } from '@/features/editor/hooks/useFetchCard';
import { useDeleteCard } from '@/features/editor/hooks/useDeleteCard';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
const Editor = () => {
    const [titleEditorOpen, setTitleEditorOpen] = useState(false);
    const [optionsOpen, setOptionsOpen] = useState(false);
    const [formError, setFormError] = useState(null);
    const [itemErrors, setItemErrors] = useState({});
    const [bannerModalOpen, setBannerModalOpen] = useState(false);
    const [avatarModalOpen, setAvatarModalOpen] = useState(false);
    const { id } = useParams();
    const { card, setCard, loading } = useFetchCard(id ? Number(id) : undefined);
    const { deleteCard } = useDeleteCard();
    const navigate = useNavigate();
    useEffect(() => {
        setFormError(null);
        setItemErrors({});
    }, [card.id]);
    const onDelete = async () => {
        try {
            await fetchCsrfToken();
            await deleteCard(card.id);
            navigate('/dashboard');
        }
        catch (error) {
            console.error('Error deleting card:', error);
        }
    };
    return (_jsxs("div", { className: "min-h-dvh bg-gray-300 pt-16 overflow-hidden", children: [_jsx(TopNav, { card: card, setOpen: setTitleEditorOpen, formError: formError, setFormError: setFormError, setItemErrors: setItemErrors }), _jsx(ColorPicker, { card: card, setCard: setCard }), _jsx(Card, { card: card, setOpen: setOptionsOpen, setCard: setCard, loading: loading, onOpenBanner: () => setBannerModalOpen(true), onOpenAvatar: () => setAvatarModalOpen(true), itemErrors: itemErrors, setItemErrors: setItemErrors }), _jsx(TitleEditor, { setCard: setCard, card: card, open: titleEditorOpen, setOpen: setTitleEditorOpen }), _jsx(Options, { card: card, setCard: setCard, open: optionsOpen, setOpen: setOptionsOpen }), _jsx(ImageUploadModal, { open: bannerModalOpen, onClose: () => setBannerModalOpen(false), onSave: (data) => setCard(prev => ({ ...prev, banner_image: data })), title: "Upload banner image" }), _jsx(ImageUploadModal, { open: avatarModalOpen, onClose: () => setAvatarModalOpen(false), onSave: (data) => setCard(prev => ({ ...prev, avatar_image: data })), title: "Upload avatar image" }), id && _jsx("button", { onClick: onDelete, className: "bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg cursor-pointer", children: "Delete" })] }));
};
export default Editor;
