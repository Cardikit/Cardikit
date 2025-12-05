import {
    Drawer,
    DrawerClose,
    DrawerContent,
} from '@/components/ui/drawer';
import Input from '@/features/auth/components/Input';
import { FaIdCard } from 'react-icons/fa';
import type { CardType } from '@/types/card';

interface TitleEditorProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    card: CardType;
    setCard: (card: CardType) => void;
}

/**
 * TitleEditor
 * -----------
 * Drawer-based editor for updating the card’s display name.
 *
 * Purpose:
 * - Provide a simple, focused UI for renaming a card.
 * - Uses a bottom drawer for mobile-friendly editing.
 *
 * Behavior:
 * - Mirrors the current card name in a controlled `<Input />`.
 * - Updates the card state via `setCard` on each keystroke.
 * - Closes the drawer when:
 *   - The user taps “Done”
 *   - The user presses Enter inside the input
 *
 * UI:
 * - Sticky grab-handle at the top for consistent drawer styling.
 * - Styled input with a card icon (`FaIdCard`) as an adornment.
 * - Uses the shared `Input` component from the auth feature.
 *
 * Props:
 * - `open`    → Whether the drawer is visible.
 * - `setOpen` → Toggles drawer visibility.
 * - `card`    → The card being edited.
 * - `setCard` → Setter to apply the updated name.
 *
 * @component
 * @since 0.0.2
 */
const TitleEditor: React.FC<TitleEditorProps> = ({ open, setOpen, card, setCard }) => {
    return (
        <Drawer open={open} onOpenChange={setOpen}>
            <DrawerContent className="bg-gray-100 px-6 py-4">
                <div className="absolute top-4 left-1/2 -translate-x-1/2 w-1/3 bg-gray-400 h-1 rounded-full cursor-grab" />
                <div className="w-full">
                    <div className="w-full flex justify-end">
                        <DrawerClose className="cursor-pointer">
                            <span className="text-gray-800">Done</span>
                        </DrawerClose>
                    </div>
                    <label htmlFor="name" className="block mb-2 text-sm font-medium text-gray-800">Edit card name</label>
                    <Input
                        id="name"
                        type="text"
                        placeholder="Card name"
                        className="w-full"
                        value={card.name}
                        onChange={(e) => setCard({ ...card, name: e.target.value })}
                        startAdornment={<FaIdCard className="text-primary-500" />}
                        autoFocus
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                setOpen(false);
                            }
                        }}
                    />
                </div>
            </DrawerContent>
        </Drawer>
    );
}

export default TitleEditor;
