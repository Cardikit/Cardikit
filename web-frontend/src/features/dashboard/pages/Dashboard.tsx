import { useState } from 'react';
import BottomNav from '@/features/dashboard/components/BottomNav';
import TopNav from '@/features/dashboard/components/TopNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import CardCarousel from '@/features/dashboard/components/CardCarousel';
import { FaPaperPlane } from 'react-icons/fa';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import type { CardType } from '@/types/card';

const Dashboard: React.FC = () => {
    const [open, setOpen] = useState(false);
    const [currentCard, setCurrentCard] = useState<CardType>({
        id: 0,
        name: 'Add Card'
    });

    const toggleMenu = () => {
        setOpen(prev => !prev);
    }

    const { cards, loading, error } = useFetchCards();

    return (
        <div className="h-dvh bg-gray-300 pt-16 overflow-hidden">
            <TopNav openMenu={toggleMenu} card={currentCard} loading={loading} />
            <div className="w-full flex flex-col items-center justify-between h-dvh pb-20">
                {/*TODO: implement QR code integration*/}
                <div className="flex-grow flex items-center justify-center">
                    <p>QR CODE</p>
                </div>
                <CardCarousel
                    setCurrentCard={setCurrentCard}
                    cardData={cards}
                    loading={loading}
                />
            </div>
            <button
                className="absolute z-20 bottom-24 right-1/2 translate-x-1/2 py-2 px-4 rounded-full flex items-center gap-2 bg-primary-500 shadow-lg cursor-pointer transition-all hover:-translate-y-1 hover:bg-primary-900 duration-200 ease-in-out"
            >
                <FaPaperPlane className="text-gray-100" />
                <span className="font-inter font-bold text-gray-100">Share</span>
            </button>
            <BottomNav />
            <NavMenu open={open} closeMenu={toggleMenu} />
        </div>
    );
}

export default Dashboard;
