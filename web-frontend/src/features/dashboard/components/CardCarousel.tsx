import { useEffect, useState } from 'react';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
    type CarouselApi
} from '@/components/ui/carousel';
import { FaPlus } from 'react-icons/fa';

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
            } else {
                setCurrentCard({
                    id: 0,
                    name: 'Add Card'
                });
            }
        };

        updateCurrentCard();
        api.on("select", updateCurrentCard);

        return () => {
            api.off("select", updateCurrentCard);
        };
    }, [api, cardData, setCurrentCard]);

    return (
        <Carousel setApi={setApi} className="w-full">
            <CarouselContent>
                {cardData.map((card) => (
                    <CarouselItem key={card.id}>
                        <div className="p-10">
                            <div className="flex items-center justify-center bg-white rounded-xl shadow h-[600px] w-full">
                                <span className="text-xl font-semibold">{card.name}</span>
                            </div>
                        </div>
                    </CarouselItem>
                ))}
                <CarouselItem id="create" className="cursor-pointer">
                        <div className="p-10">
                            <div className="flex flex-col space-y-4 items-center justify-center bg-white rounded-xl shadow h-[600px] w-full">
                                <div className="p-4 rounded-full bg-red-100">
                                    <FaPlus className="text-3xl text-primary-500" />
                                </div>
                                <span className="text-xl font-semibold text-gray-800 font-inter">Add card</span>
                            </div>
                        </div>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious className="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 size-10 cursor-pointer" />
            <CarouselNext className="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 size-10 cursor-pointer" />
        </Carousel>
    );
};

export default CardCarousel;

