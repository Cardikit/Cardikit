import type { Dispatch, SetStateAction } from 'react';
import type { CardType } from '@/types/card';
import type { ThemeMeta } from '@/types/theme';

interface ThemePickerProps {
    card: CardType;
    setCard: Dispatch<SetStateAction<CardType>>;
    options: ThemeMeta[];
}

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
