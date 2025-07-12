import { useEffect, useState } from 'react';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
    type CarouselApi
} from '@/components/ui/carousel';

interface Card {
    id: number;
    name: string;
}

interface CardCarouselProps {
    cardData: Card[];
    setCurrentCard: (card: Card) => void;
}

const CardCarousel: React.FC<CardCarouselProps> = ({ setCurrentCard, cardData }) => {
    const [api, setApi] = useState<CarouselApi | undefined>();

    useEffect(() => {
        if (!api) return;

        const updateCurrentCard = () => {
            const currentIndex = api.selectedScrollSnap();
            const currentCard = cardData[currentIndex];
            if (currentCard) {
                setCurrentCard(currentCard);
            }
        };

        updateCurrentCard(); // set initial card
        api.on("select", updateCurrentCard);

        return () => {
            api.off("select", updateCurrentCard); // cleanup listener
        };
    }, [api, cardData, setCurrentCard]);

    return (
        <Carousel setApi={setApi} className="w-1/2">
            <CarouselContent>
                {cardData.map((card) => (
                    <CarouselItem key={card.id}>
                        <div className="p-1">
                            <div className="flex aspect-square items-center justify-center p-6 bg-white rounded-xl shadow">
                                <span className="text-xl font-semibold">{card.name}</span>
                            </div>
                        </div>
                    </CarouselItem>
                ))}
            </CarouselContent>
            <CarouselPrevious />
            <CarouselNext />
        </Carousel>
    );
};

export default CardCarousel;

