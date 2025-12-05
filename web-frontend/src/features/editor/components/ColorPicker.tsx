import type { CardType } from '@/types/card';

interface ColorPickerProps {
    card: CardType;
    setCard: React.Dispatch<React.SetStateAction<CardType>>;
    className?: string;
    variant?: 'default' | 'compact';
}

const COLOR_OPTIONS = [
    '#1D4ED8', // blue
    '#2563EB',
    '#DB2777', // pink
    '#F97316', // orange
    '#059669', // green
    '#10B981', // mint
    '#0EA5E9', // sky
    '#7C3AED', // purple
    '#F59E0B', // amber
    '#EF4444', // red
];

/**
 * ColorPicker
 * -----------
 * Accent color selector for a Card within the editor.
 *
 * Responsibilities:
 * - Displays a fixed palette of brand-safe accent colors (`COLOR_OPTIONS`).
 * - Highlights the currently selected color based on `card.color`, or
 *   falls back to the first palette color if none is set.
 * - Updates the card's `color` field via `setCard` when a color is chosen.
 *
 * UI details:
 * - Two layout variants:
 *   - `default`  → padded, centered layout for full editor sections.
 *   - `compact`  → minimal top padding for inline/stacked usage.
 * - Active color is indicated with a thicker dark border and a small
 *   white dot in the center.
 * - Buttons are round, accessible, and include an `aria-label` for screen readers.
 *
 * Props:
 * - `card`       → Current card whose accent color is being edited.
 * - `setCard`    → Setter to update the card state with the new color.
 * - `className`  → Optional additional classes for outer container styling.
 * - `variant`    → `"default"` | `"compact"` layout mode (default: `"default"`).
 *
 * @component
 * @since 0.0.2
 */
const ColorPicker: React.FC<ColorPickerProps> = ({ card, setCard, className = '', variant = 'default' }) => {
    const selected = card.color ?? COLOR_OPTIONS[0];
    const containerClasses = variant === 'compact'
        ? 'pt-2'
        : 'px-6 md:w-full md:flex md:justify-center pt-4';

    return (
        <div className={`${containerClasses} ${className}`}>
            <div>
                <p className="text-sm text-gray-700 font-semibold mb-2 font-inter">Accent color</p>
                <div className="flex flex-wrap gap-3">
                    {COLOR_OPTIONS.map(color => {
                        const isActive = selected === color;
                        return (
                            <button
                                key={color}
                                type="button"
                                className={[
                                    'w-10 h-10 rounded-full border-2 transition-transform',
                                    'flex items-center justify-center cursor-pointer',
                                    isActive ? 'border-gray-900 scale-105' : 'border-transparent',
                                ].join(' ')}
                                style={{ backgroundColor: color }}
                                aria-label={`Select accent color ${color}`}
                                onClick={() => setCard(prev => ({ ...prev, color }))}
                            >
                                {isActive && <span className="w-3 h-3 rounded-full bg-white" />}
                            </button>
                        );
                    })}
                </div>
            </div>
        </div>
    );
};

export default ColorPicker;
