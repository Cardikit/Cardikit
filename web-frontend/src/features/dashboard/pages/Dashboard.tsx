import { useState } from 'react';
import BottomNav from '@/features/dashboard/components/BottomNav';
import TopNav from '@/features/dashboard/components/TopNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import QrCode from '@/features/dashboard/components/QrCode';
import CardCarousel from '@/features/dashboard/components/CardCarousel';
import EditQrDrawer from '@/features/dashboard/components/EditQrDrawer';
import LogoModal from '@/features/dashboard/components/LogoModal';
import { FaPaperPlane } from 'react-icons/fa';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import type { CardType } from '@/types/card';

const Dashboard: React.FC = () => {
    const [open, setOpen] = useState(false);
    const [logoModalOpen, setLogoModalOpen] = useState(false);
    const [editQrOpen, setEditQrOpen] = useState(false);
    const [currentCard, setCurrentCard] = useState<CardType>({
        id: 0,
        name: 'Add Card',
        color: '#1D4ED8',
        items: [],
    });

    const toggleMenu = () => {
        setOpen(prev => !prev);
    }


    const { cards, loading, error, refresh } = useFetchCards();

    return (
        <div className="h-dvh bg-gray-300 pt-16 overflow-hidden">
            <TopNav openMenu={toggleMenu} card={currentCard} loading={loading} />
            <div className="w-full flex flex-col items-center justify-between h-dvh pb-20">
                <QrCode currentCard={currentCard} loading={loading} setOpen={setEditQrOpen} />
                <CardCarousel
                    setCurrentCard={setCurrentCard}
                    cardData={cards}
                    loading={loading}
                />
            </div>
            <button
                className="absolute z-20 bottom-24 right-1/2 translate-x-1/2 py-2 px-4 rounded-full flex items-center gap-2 bg-primary-500 shadow-lg cursor-pointer transition-all hover:-translate-y-1 hover:bg-primary-900 duration-200 ease-in-out"
                onClick={() => setEditQrOpen(true)}
            >
                <FaPaperPlane className="text-gray-100" />
                <span className="font-inter font-bold text-gray-100">Share</span>
            </button>
            <BottomNav />
            <NavMenu open={open} closeMenu={toggleMenu} />
            <EditQrDrawer
                open={editQrOpen}
                setOpen={setEditQrOpen}
                currentCard={currentCard}
                setLogoModalOpen={setLogoModalOpen}
            />
            {logoModalOpen && (
                <LogoModal
                    refreshCards={refresh}
                    currentCard={currentCard}
                    setLogoModalOpen={setLogoModalOpen}
                />
            )}
        </div>
    );
}

export default Dashboard;
