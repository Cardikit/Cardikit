import type { CardType } from '@/types/card';
interface CardProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    setCard: React.Dispatch<React.SetStateAction<CardType>>;
    loading: boolean;
    onOpenBanner: () => void;
    onOpenAvatar: () => void;
    itemErrors?: Record<string, string>;
    setItemErrors?: React.Dispatch<React.SetStateAction<Record<string, string>>>;
}
declare const Card: React.FC<CardProps>;
export default Card;
