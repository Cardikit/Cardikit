import { useEffect, useState } from 'react';
import TopNav from '@/features/editor/components/TopNav';
import TitleEditor from '@/features/editor/components/TitleEditor';
import Card from '@/features/editor/components/Card';
import Options from '@/features/editor/components/Options';
import ColorPicker from '@/features/editor/components/ColorPicker';
import ThemePicker from '@/features/editor/components/ThemePicker';
import ImageUploadModal from '@/features/editor/components/ImageUploadModal';
import DesktopNav from '@/features/dashboard/components/DesktopNav';
import { useParams, useNavigate } from 'react-router-dom';
import { useFetchCard } from '@/features/editor/hooks/useFetchCard';
import { useDeleteCard } from '@/features/editor/hooks/useDeleteCard';
import { useThemes } from '@/features/editor/hooks/useThemes';
import { useAuth } from '@/contexts/AuthContext';

/**
 * Editor
 * ------
 * Full-screen card editor for creating and updating Cardikit cards.
 * Orchestrates data fetching, layout, side panels, and all editing flows.
 *
 * High-level responsibilities:
 * - Determine editor mode:
 *   - Existing card: uses `id` route param + `useFetchCard(cardId)`.
 *   - New card: initializes via `useFetchCard(undefined)` (client-side defaults).
 * - Manage global editor UI state:
 *   - Title editor drawer visibility.
 *   - Field options drawer visibility.
   *   - Banner & avatar image modals.
 *   - Per-form and per-item validation errors.
 *   - Delete confirmation modal.
 *
 * Data & side effects:
 * - `useFetchCard(cardId)`:
 *   - Provides `card`, `setCard`, and `loading` for the main editor state.
 * - `useThemes()`:
 *   - Loads available themes and ensures the card always has a valid theme.
 *   - If the current theme is missing/unknown, applies the first available theme.
 * - `useDeleteCard()`:
 *   - Deletes the current card and navigates back to `/` on success.
 * - Resets `formError` and `itemErrors` whenever `card.id` changes (e.g., switching
 *   from “new” to “saved” state or loading a different card).
 *
 * Layout & components:
 * - Fixed top bar (`TopNav`):
 *   - Handles saving (create/update), validation, and top-level error display.
 *   - Opens the `TitleEditor` for renaming the card.
 *
 * - Main content:
 *   - Desktop sidebar (`DesktopNav`) mirrored from the dashboard.
 *   - Skeleton placeholder when loading an existing card (`isLoadingExisting`).
 *   - Responsive 2-column editor layout on desktop:
 *     - Right/aside (`Card settings` panel):
 *       - Accent color selection (`ColorPicker`).
 *       - Theme selection (`ThemePicker`).
 *       - Compact summary: card name, field count, current color.
 *       - “Danger zone” card delete controls (desktop + mobile variants).
 *     - Left/main:
 *       - Editable card preview (`Card` component) with:
 *         - Drag-and-drop field reordering.
 *         - Inline editing.
 *         - Banner & avatar triggers.
 *
 * - Modals & drawers:
 *   - `TitleEditor` → Edits the card title.
 *   - `Options`     → Field picker to add new items to the card.
 *   - `ImageUploadModal` (banner) → Upload/clear banner image.
 *   - `ImageUploadModal` (avatar) → Upload/clear avatar image.
 *   - Custom bottom delete confirmation sheet for existing cards.
 *
 * UX details:
 * - “Danger zone” appears only for existing cards (`isExistingCard`).
 * - Delete confirmation clearly communicates irreversibility.
 * - All destructive actions require explicit confirmation.
 *
 * @component
 * @since 0.0.2
 */
const Editor: React.FC = () => {
    const [titleEditorOpen, setTitleEditorOpen] = useState(false);
    const [optionsOpen, setOptionsOpen] = useState(false);
    const [formError, setFormError] = useState<string | null>(null);
    const [itemErrors, setItemErrors] = useState<Record<string, string>>({});
    const [bannerModalOpen, setBannerModalOpen] = useState(false);
    const [avatarModalOpen, setAvatarModalOpen] = useState(false);
    const [confirmDeleteOpen, setConfirmDeleteOpen] = useState(false);
    const { id } = useParams();
    const cardId = id ? Number(id) : undefined;
    const { card, setCard, loading } = useFetchCard(cardId);
    const { deleteCard } = useDeleteCard();
    const { themes } = useThemes();
    const { user } = useAuth();
    const navigate = useNavigate();
    const isExistingCard = Boolean(id);
    const isLoadingExisting = loading && isExistingCard;
    const role = user?.role ?? 0;

    useEffect(() => {
        setFormError(null);
        setItemErrors({});
    }, [card.id]);

    useEffect(() => {
        if (!themes.length) return;
        const hasTheme = themes.some(theme => theme.slug === card.theme);
        if (!hasTheme) {
            setCard(prev => ({ ...prev, theme: themes[0].slug }));
        }
    }, [themes, card.theme, setCard]);

    const onDelete = async () => {
        try {
            await deleteCard(card.id);
            navigate('/');
        } catch (error) {
            console.error('Error deleting card:', error);
        }
    }

    return (
        <div className="min-h-dvh bg-gray-300 pt-16 md:pt-24 overflow-x-hidden">
            <TopNav
                card={card}
                setOpen={setTitleEditorOpen}
                formError={formError}
                setFormError={setFormError}
                setItemErrors={setItemErrors}
            />
            <div className="w-full h-full pb-20 px-4 lg:px-8 flex flex-col">
                <DesktopNav />
                {isLoadingExisting ? (
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
                    <div className="flex flex-col lg:flex-row lg:items-start lg:gap-2">
                        <aside className="order-1 lg:order-2 w-full lg:w-80 xl:w-96 2xl:w-[26rem] space-y-4 lg:sticky lg:top-24">
                            <div className="bg-white rounded-xl shadow p-4 space-y-4">
                                <h3 className="text-lg font-bold text-gray-900 font-inter">Card settings</h3>
                                <p className="text-sm text-gray-600 font-inter">Update the accent color and review card basics.</p>
                                <ColorPicker card={card} setCard={setCard} variant="compact" className="px-0" />
                                <ThemePicker card={card} setCard={setCard} options={themes} />
                                <div className="bg-gray-50 rounded-lg p-3 space-y-1">
                                    <p className="text-sm font-semibold text-gray-800 font-inter truncate">{card.name}</p>
                                    <p className="text-xs text-gray-600 font-inter">
                                        {(card.items?.length || 0)} fields • Color {card.color || '#1D4ED8'}
                                    </p>
                                </div>
                            </div>
                            {isExistingCard && (
                                <div className="bg-white rounded-xl shadow p-4 space-y-3 hidden lg:block">
                                    <h3 className="text-lg font-bold text-gray-900 font-inter">Danger zone</h3>
                                    <p className="text-sm text-gray-600 font-inter">Delete this card and all of its fields.</p>
                                    <button
                                        onClick={() => setConfirmDeleteOpen(true)}
                                        className="w-full bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg cursor-pointer hover:bg-red-600 transition-colors font-semibold"
                                    >
                                        Delete card
                                    </button>
                                </div>
                            )}
                        </aside>
                        <div className="order-2 lg:order-1 flex-1 flex justify-center">
                            <div className="hidden lg:block w-1/3 xl:w-1/2" />
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
                        </div>
                        {isExistingCard && (
                            <div className="order-3 bg-white rounded-xl shadow p-4 space-y-3 lg:hidden">
                                <h3 className="text-lg font-bold text-gray-900 font-inter">Danger zone</h3>
                                <p className="text-sm text-gray-600 font-inter">Delete this card and all of its fields.</p>
                                <button
                                    onClick={() => setConfirmDeleteOpen(true)}
                                    className="w-full bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg cursor-pointer hover:bg-red-600 transition-colors font-semibold"
                                >
                                    Delete card
                                </button>
                            </div>
                        )}
                    </div>
                )}
            </div>
            <TitleEditor setCard={setCard} card={card} open={titleEditorOpen} setOpen={setTitleEditorOpen} />
            <Options card={card} setCard={setCard} open={optionsOpen} setOpen={setOptionsOpen} />
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
            {isExistingCard && (
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
