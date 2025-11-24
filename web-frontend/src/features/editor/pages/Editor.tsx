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

const Editor: React.FC = () => {
    const [titleEditorOpen, setTitleEditorOpen] = useState(false);
    const [optionsOpen, setOptionsOpen] = useState(false);
    const [formError, setFormError] = useState<string | null>(null);
    const [itemErrors, setItemErrors] = useState<Record<string, string>>({});
    const [bannerModalOpen, setBannerModalOpen] = useState(false);
    const [avatarModalOpen, setAvatarModalOpen] = useState(false);
    const [confirmDeleteOpen, setConfirmDeleteOpen] = useState(false);
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
        } catch (error) {
            console.error('Error deleting card:', error);
        }
    }

    return (
        <div className="min-h-dvh bg-gray-300 pt-16 overflow-hidden">
            <TopNav
                card={card}
                setOpen={setTitleEditorOpen}
                formError={formError}
                setFormError={setFormError}
                setItemErrors={setItemErrors}
            />
            {loading && id ? (
                <div className="p-10 space-y-4" data-testid="editor-skeleton">
                    <div className="h-6 w-40 bg-gray-200 rounded animate-pulse" />
                    <div className="h-4 w-24 bg-gray-200 rounded animate-pulse" />
                    <div className="flex bg-white rounded-xl shadow h-[600px] w-full p-4 flex-col space-y-4 animate-pulse">
                        <div className="h-32 w-full rounded-lg bg-gray-200" />
                        <div className="flex justify-center -mt-10">
                            <div className="w-20 h-20 rounded-full bg-gray-200 border-4 border-white" />
                        </div>
                        {Array.from({ length: 4 }).map((_, idx) => (
                            <div key={idx} className="w-full rounded-lg bg-gray-100 h-16" />
                        ))}
                    </div>
                </div>
            ) : (
                <>
                    <ColorPicker card={card} setCard={setCard} />
                    <Card
                        card={card}
                        setOpen={setOptionsOpen}
                        setCard={setCard}
                        loading={loading}
                        onOpenBanner={() => setBannerModalOpen(true)}
                        onOpenAvatar={() => setAvatarModalOpen(true)}
                        itemErrors={itemErrors}
                        setItemErrors={setItemErrors}
                    />
                    <TitleEditor setCard={setCard} card={card} open={titleEditorOpen} setOpen={setTitleEditorOpen} />
                    <Options card={card} setCard={setCard} open={optionsOpen} setOpen={setOptionsOpen} />
                    {id && (
                        <div className="px-10 pb-6">
                            <button
                                onClick={() => setConfirmDeleteOpen(true)}
                                className="w-full bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg cursor-pointer hover:bg-red-600 transition-colors font-semibold"
                            >
                                Delete card
                            </button>
                        </div>
                    )}
                </>
            )}
            <ImageUploadModal
                open={bannerModalOpen}
                onClose={() => setBannerModalOpen(false)}
                onSave={(data) => setCard(prev => ({ ...prev, banner_image: data }))}
                title="Upload banner image"
            />
            <ImageUploadModal
                open={avatarModalOpen}
                onClose={() => setAvatarModalOpen(false)}
                onSave={(data) => setCard(prev => ({ ...prev, avatar_image: data }))}
                title="Upload avatar image"
            />
            {id && (
                <div className={`${confirmDeleteOpen ? 'block' : 'hidden'}`}>
                    <div className="fixed inset-0 bg-black/40 z-40" onClick={() => setConfirmDeleteOpen(false)} />
                    <div className="fixed inset-x-4 bottom-10 bg-white rounded-2xl shadow-2xl z-50 p-6 space-y-4">
                        <h3 className="text-lg font-bold text-gray-900">Delete this card?</h3>
                        <p className="text-gray-600 text-sm">
                            This action cannot be undone. All items and media for this card will be removed.
                        </p>
                        <div className="flex space-x-3">
                            <button
                                onClick={() => setConfirmDeleteOpen(false)}
                                className="flex-1 py-2 rounded-lg border border-gray-300 text-gray-800 font-semibold hover:bg-gray-100 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                onClick={async () => {
                                    await onDelete();
                                    setConfirmDeleteOpen(false);
                                }}
                                className="flex-1 py-2 rounded-lg bg-red-500 text-white font-semibold shadow hover:bg-red-600 transition-colors"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

export default Editor;
