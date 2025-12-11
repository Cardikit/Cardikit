import type { CardType } from '@/types/card';

interface CardDropdownProps {
    cards: CardType[];
    selected: number | null;
    onChange: (id: number | null) => void;
    loading?: boolean;
}

const CardDropdown: React.FC<CardDropdownProps> = ({ cards, selected, onChange, loading }) => {
    return (
        <div className="flex items-center gap-2">
            <label className="text-sm text-gray-600">Card</label>
            <select
                value={selected ?? 'all'}
                onChange={e => {
                    const val = e.target.value;
                    onChange(val === 'all' ? null : Number(val));
                }}
                className="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white shadow-sm"
            >
                <option value="all">All cards</option>
                {loading ? (
                    <option>Loading...</option>
                ) : (
                    cards.map(card => (
                        <option key={card.id} value={card.id}>
                            {card.name}
                        </option>
                    ))
                )}
            </select>
        </div>
    );
};

export default CardDropdown;
