import { useState } from 'react';
import BottomNav from '@/features/dashboard/components/BottomNav';
import TopNav from '@/features/dashboard/components/TopNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import CardCarousel from '@/features/dashboard/components/CardCarousel';

const cardData = [
    { id: 1, name: 'Personal Card' },
    { id: 2, name: 'Work Card' },
    { id: 3, name: 'Freelance Card' },
    { id: 4, name: 'Conference Card' },
    { id: 5, name: 'Event Card' },
];

interface Card {
    id: number;
    name: string;
}

const Dashboard: React.FC = () => {
    const [open, setOpen] = useState(false);
    const [currentCard, setCurrentCard] = useState<Card>({
        id: 0,
        name: ''
    });

    const toggleMenu = () => {
        setOpen(prev => !prev);
    }

    return (
        <div className="min-h-dvh bg-gray-300 pt-16 overflow-x-hidden">
            <TopNav openMenu={toggleMenu} card={currentCard} />
            <div className="w-full flex items-center justify-center">
                <CardCarousel
                    setCurrentCard={setCurrentCard}
                    cardData={cardData}
                />
            </div>
            <BottomNav />
            <NavMenu open={open} closeMenu={toggleMenu} />
        </div>
    );
}

export default Dashboard;
