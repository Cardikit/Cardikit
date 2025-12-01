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
