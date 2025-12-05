import { MdModeEdit } from 'react-icons/md';
import { Link, useParams, useNavigate } from 'react-router-dom';
import type { CardType } from '@/types/card';
import { useSaveCard } from '@/features/editor/hooks/useSaveCard';

interface TopNavProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    formError: string | null;
    setFormError: (error: string | null) => void;
    setItemErrors: (errors: Record<string, string>) => void;
}

/**
 * TopNav (Editor)
 * ----------------
 * Top navigation bar for the Card editor screen. Handles both the UI header
 * and the full save lifecycle (create/update, validation, and error display).
 *
 * Responsibilities:
 * - Display the editor header:
 *   - "Cancel" link back to the dashboard (`/`).
 *   - Editable card title (opens `TitleEditor` via `setOpen(true)`).
 *   - "Save" action that triggers validation and API calls.
 *
 * - Build and submit the card payload:
 *   - Normalizes name, color, and item values (trimmed strings).
 *   - Applies defaults:
 *     - `color` → `#1D4ED8` when missing.
 *     - `theme` → `"default"` when missing.
 *   - Maps `card.items` to `card_items` for the API.
 *
 * - Validation & error handling:
 *   - Delegates to useSaveCard, which runs schema validation and maps errors:
 *     - Sets `formError` for top-level name errors.
 *     - Maps item-level errors into `setItemErrors`, keyed by item id/client_id.
 *     - Surfaces a user-friendly error string for display.
 *
 * - Create vs update:
 *   - Uses `useParams` to check for `id` in the URL.
 *   - If `id` exists → calls update.
 *   - Otherwise → calls create.
 *   - On success → navigates back to `/`.
 *
 * - Loading state:
 *   - Combines create/update into a single `saving` flag.
 *   - While saving: disables Save and shows `Saving...`.
 *
 * Props:
 * - `card`         → Current card being edited.
 * - `setOpen`      → Opens the title editor drawer.
 * - `formError`    → External top-level form error (e.g., from parent validations).
 * - `setFormError` → Setter to update the top-level form error message.
 * - `setItemErrors`→ Setter for per-item validation errors (by item key).
 *
 * @component
 * @since 0.0.2
 */
const TopNav: React.FC<TopNavProps> = ({ card, setOpen, formError, setFormError, setItemErrors }) => {
    const { save, saving, error: saveError } = useSaveCard();
    const navigate = useNavigate();
    const { id } = useParams();

    const onSubmit = async () => {
        const normalizedCard: CardType = {
            ...card,
            name: (card.name ?? '').trim(),
            color: (card.color ?? '#1D4ED8').trim(),
            theme: card.theme ?? 'default',
            banner_image: card.banner_image ?? null,
            avatar_image: card.avatar_image ?? null,
            items: (card.items ?? []).map(item => ({
                ...item,
                value: (item.value ?? '').trim(),
            }))
        };

        try {
            await save({
                card: normalizedCard,
                cardId: id ? Number(id) : undefined,
                setFormError,
                setItemErrors,
                onSuccess: () => navigate('/'),
            });
        } catch {
            return;
        }
    }

    return (
        <div className="fixed top-0 w-full z-10 px-4 md:px-8 py-3 md:py-6 lg:px-10 lg:py-4 text-gray-800 bg-gray-300/80 backdrop-blur">
            <div className="flex items-center justify-between gap-4 max-w-6xl mx-auto">
                <Link to="/" className="font-inter md:text-lg cursor-pointer">Cancel</Link>
                <div onClick={() => setOpen(true)} className="flex items-center space-x-2 cursor-pointer">
                    <h1 className="text-xl md:text-2xl font-semibold font-inter">{card.name}</h1>
                    <MdModeEdit className="text-2xl" />
                </div>
                <p
                    onClick={!saving ? onSubmit : undefined}
                    className={`font-inter md:text-lg ${saving ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer'}`}
                >
                    {saving ? 'Saving...' : 'Save'}
                </p>
            </div>
            {(formError || saveError) && (
                <p className="text-red-600 text-sm text-center mt-2 font-inter">
                    {formError || saveError}
                </p>
            )}
        </div>
    );
}

export default TopNav;
