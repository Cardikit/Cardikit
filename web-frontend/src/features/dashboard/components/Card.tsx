import type { CardType } from '@/types/card';

interface CardProps {
    card: CardType
}

const Card: React.FC<CardProps> = ({ card }) => {

    return (
        <div className="p-10">
            <div className="flex items-center justify-center bg-white rounded-xl shadow h-[600px] w-full">
                <span className="text-xl font-semibold">{card.name}</span>
            </div>
        </div>
    );
}

export default Card;
