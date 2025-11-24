import type { CardType } from '@/types/card';
interface CardCarouselProps {
    cardData: CardType[];
    setCurrentCard: (card: CardType) => void;
    loading: boolean;
}
declare const CardCarousel: React.FC<CardCarouselProps>;
export default CardCarousel;
