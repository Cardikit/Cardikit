import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { useEffect, useState } from 'react';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import CardComponent from '@/features/dashboard/components/Card';
import CardSkeleton from '@/features/dashboard/components/CardSkeleton';
import AddCard from '@/features/dashboard/components/AddCard';
const CardCarousel = ({ setCurrentCard, cardData, loading }) => {
    const [api, setApi] = useState();
    useEffect(() => {
        if (!api)
            return;
        const updateCurrentCard = () => {
            const currentIndex = api.selectedScrollSnap();
            const currentCard = cardData[currentIndex];
            if (currentCard) {
                setCurrentCard(currentCard);
            }
            else {
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
    return (_jsxs(Carousel, { setApi: setApi, className: "w-full", children: [_jsxs(CarouselContent, { children: [loading ? Array.from({ length: 3 }).map((_, index) => (_jsx(CarouselItem, { children: _jsx(CardSkeleton, {}) }, `card-skeleton-${index}`))) : cardData.map((card) => (_jsx(CarouselItem, { children: _jsx(CardComponent, { card: card }) }, card.id))), _jsx(CarouselItem, { id: "create", children: _jsx(AddCard, {}) })] }), _jsx(CarouselPrevious, { className: "absolute left-4 top-1/3 transform -translate-y-1/2 z-10 size-10 cursor-pointer" }), _jsx(CarouselNext, { className: "absolute right-4 top-1/3 transform -translate-y-1/2 z-10 size-10 cursor-pointer" })] }));
};
export default CardCarousel;
