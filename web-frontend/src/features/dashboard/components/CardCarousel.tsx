import { useEffect, useState } from 'react';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
    type CarouselApi
} from '@/components/ui/carousel';
import type { CardType } from '@/types/card';
import CardComponent from '@/features/dashboard/components/Card';
import CardSkeleton from '@/features/dashboard/components/CardSkeleton';
import AddCard from '@/features/dashboard/components/AddCard';

interface CardCarouselProps {
    cardData: CardType[];
    setCurrentCard: (card: CardType) => void;
    loading: boolean;
}

const CardCarousel: React.FC<CardCarouselProps> = ({ setCurrentCard, cardData, loading }) => {
    const [api, setApi] = useState<CarouselApi | undefined>();

    useEffect(() => {
        if (!api) return;

        const updateCurrentCard = () => {
            const currentIndex = api.selectedScrollSnap();
            const currentCard = cardData[currentIndex];
            if (currentCard) {
                setCurrentCard(currentCard);
            } else {
                setCurrentCard({
                    id: 0,
                    name: 'Add Card',
                    color: '#1D4ED8',
                    items: []
                });
            }
        };

        updateCurrentCard();
        api.on("select", updateCurrentCard);

        return () => {
            api.off("select", updateCurrentCard);
        };
    }, [api, cardData, setCurrentCard, loading]);

    return (
        <Carousel setApi={setApi} className="w-full">
            <CarouselContent>
                {loading ? Array.from({ length: 3 }).map((_, index) => (
                    <CarouselItem key={`card-skeleton-${index}`}>
                        <CardSkeleton />
                    </CarouselItem>
                    )) : cardData.map((card) => (
                        <CarouselItem key={card.id}>
                            <CardComponent card={card} />
                        </CarouselItem>
                    ))}
                <CarouselItem id="create">
                    <AddCard />
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious className="absolute left-4 md:left-16 top-1/3 transform -translate-y-1/2 z-10 size-10 cursor-pointer" />
            <CarouselNext className="absolute right-4 md:right-16 top-1/3 transform -translate-y-1/2 z-10 size-10 cursor-pointer" />
        </Carousel>
    );
};

export default CardCarousel;
