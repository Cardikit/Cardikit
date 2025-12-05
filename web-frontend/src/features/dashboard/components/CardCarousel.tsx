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

/**
 * CardCarousel
 * ------------
 * A horizontally swipeable carousel for browsing the user's cards within
 * the Cardikit dashboard.
 *
 * Responsibilities:
 * - Displays each card using `CardComponent`, preserving full visual layout.
 * - Appends an additional slide containing the `AddCard` component, allowing
 *   users to quickly create a new card directly from the carousel.
 * - Shows loading placeholders (`CardSkeleton`) while data is being fetched.
 *
 * Carousel integration:
 * - Receives and stores the `CarouselApi` via `setApi`.
 * - Syncs the currently selected carousel slide with the parent component by:
 *   - Listening to the `"select"` event on the carousel API.
 *   - Determining the active index via `api.selectedScrollSnap()`.
 *   - Passing the active card to `setCurrentCard`.
 *   - Falling back to a placeholder "Add Card" object if the index points to
 *     the creation slide instead of an actual card.
 *
 * UX notes:
 * - Fully responsive layout with arrow controls positioned differently across breakpoints.
 * - Allows seamless swiping through real cards, skeletons (during load), and the add-card tile.
 *
 * Props:
 * - `cardData`: Array of `CardType` objects representing all user cards.
 * - `setCurrentCard`: Callback to update the externally managed selected card.
 * - `loading`: When true, shows skeleton cards instead of real card data.
 *
 * @component
 * @since 0.0.2
 */
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
            <CarouselPrevious className="absolute left-4 md:left-16 lg:left-48 top-1/3 transform -translate-y-1/2 z-10 size-10 cursor-pointer" />
            <CarouselNext className="absolute right-4 md:right-16 lg:right-48 top-1/3 transform -translate-y-1/2 z-10 size-10 cursor-pointer" />
        </Carousel>
    );
};

export default CardCarousel;
