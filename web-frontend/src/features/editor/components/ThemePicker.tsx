import type { Dispatch, SetStateAction } from 'react';
import type { CardType } from '@/types/card';
import type { ThemeMeta } from '@/types/theme';

interface ThemePickerProps {
    card: CardType;
    setCard: Dispatch<SetStateAction<CardType>>;
    options: ThemeMeta[];
}

/**
 * ThemePicker
 * -----------
 * Selector for choosing which visual theme a Card should use.
 *
 * Responsibilities:
 * - Determine the currently selected theme:
 *   - Uses `card.theme` when present.
 *   - Falls back to the first provided option’s `slug`.
 *   - Ultimately falls back to `"default"` if no options are provided.
 * - Render a list of available theme options as buttons.
 * - Update the card’s `theme` property via `setCard` when a theme is selected.
 *
 * UI details:
 * - Labelled section titled “Theme”.
 * - Displays options in a 2-column grid for compact, scannable selection.
 * - Highlights the active theme with:
 *   - Primary-colored border and light background.
 *   - Slight shadow for emphasis.
 * - Each theme button shows:
 *   - Theme name (primary text).
 *   - Theme slug (secondary, subdued text).
 *
 * Props:
 * - `card`    → Current card being edited (reads `card.theme`).
 * - `setCard` → Setter used to apply the new `theme` slug to the card.
 * - `options` → List of available themes (`ThemeMeta[]`), each with `slug` and `name`.
 *               If empty, a fallback “Default” option is shown.
 *
 * @component
 * @since 0.0.2
 */
const ThemePicker: React.FC<ThemePickerProps> = ({ card, setCard, options }) => {
    const selected = card.theme ?? options[0]?.slug ?? 'default';
    const available = options.length ? options : [{ slug: 'default', name: 'Default' }];

    return (
        <div className="space-y-2">
            <p className="text-sm text-gray-700 font-semibold font-inter">Theme</p>
            <div className="grid grid-cols-2 gap-3">
                {available.map(theme => {
                    const isActive = theme.slug === selected;
                    return (
                        <button
                            key={theme.slug}
                            type="button"
                            onClick={() => setCard(prev => ({ ...prev, theme: theme.slug }))}
                            className={[
                                'border rounded-xl px-3 py-2 text-left transition-all',
                                isActive ? 'border-primary-500 bg-primary-50 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300',
                            ].join(' ')}
                        >
                            <span className="block text-sm font-semibold text-gray-900">{theme.name}</span>
                            <span className="block text-xs text-gray-600">{theme.slug}</span>
                        </button>
                    );
                })}
            </div>
        </div>
    );
}

export default ThemePicker;
