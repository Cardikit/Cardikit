import { useState } from 'react';
import BottomNav from '@/features/dashboard/components/BottomNav';
import TopNav from '@/features/dashboard/components/TopNav';
import NavMenu from '@/features/dashboard/components/NavMenu';
import QrCode from '@/features/dashboard/components/QrCode';
import CardCarousel from '@/features/dashboard/components/CardCarousel';
import EditQrDrawer from '@/features/dashboard/components/EditQrDrawer';
import LogoModal from '@/features/dashboard/components/LogoModal';
import DesktopNav from '@/features/dashboard/components/DesktopNav';
import { FaPaperPlane } from 'react-icons/fa';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import type { CardType } from '@/types/card';
import { Link } from 'react-router-dom';

/**
 * Dashboard
 * ---------
 * The main authenticated dashboard screen of the Cardikit application.
 * Acts as the layout and state coordinator for:
 *  - Viewing cards
 *  - Browsing and selecting cards (carousel)
 *  - Displaying and sharing QR codes
 *  - Editing and creating cards
 *  - Opening the sidebar navigation menu (mobile/tablet)
 *  - Desktop actions sidebar
 *
 * High-level responsibilities:
 * ----------------------------
 * - Fetch all cards using `useFetchCards` and manage loading state.
 * - Track `currentCard` and update it when the user scrolls through the carousel.
 * - Manage visibility of UI overlays:
 *   - `NavMenu` (mobile navigation)
 *   - `EditQrDrawer` (QR sharing actions)
 *   - `LogoModal` (add logos to QR codes)
 *
 * Layout summary:
 * ---------------
 * - **Top navigation bar:** Shows the current card name, menu toggle, and edit button.
 * - **Desktop sidebar (`DesktopNav`):** Replaces the bottom nav on large screens.
 * - **Center content:**
 *     - QR code preview (`QrCode`)
 *     - Card carousel (`CardCarousel`)
 * - **Desktop actions sidebar:**
 *     - Share button
 *     - Edit / create card links
 *     - Current card metadata summary
 * - **Mobile-only floating Share button** for quick access to QR drawer.
 * - **Bottom navigation (`BottomNav`)** for mobile users.
 *
 * Behavior details:
 * -----------------
 * - On carousel index change, `currentCard` updates automatically.
 * - When selecting the “Add Card” tile, a placeholder card is used.
 * - The share drawer (`EditQrDrawer`) receives the active card and allows
 *   link copying, QR downloading, Web Share API usage, and logo insertion.
 * - The logo modal triggers a card refresh on save to update the QR preview.
 *
 * State managed here:
 * -------------------
 * - `open`           → mobile nav drawer
 * - `logoModalOpen`  → QR logo editing modal
 * - `editQrOpen`     → QR share drawer
 * - `currentCard`    → selected card from carousel
 *
 * @component
 * @since 0.0.2
 */
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

    const { cards, loading, refresh } = useFetchCards();

    return (
        <div className="h-dvh bg-gray-300 pt-16 md:pt-24 overflow-hidden">
            <TopNav openMenu={toggleMenu} card={currentCard} loading={loading} />
            <div className="w-full h-full pb-20 px-4 lg:px-8 flex flex-col">

                {/* DESKTOP SIDEBAR */}
                <DesktopNav />
                {/* END DESKTOP SIDEBAR */}

                <div className="flex flex-col items-center space-y-6 lg:col-span-6 xl:col-span-7">
                    <div className="w-full flex justify-center">
                        <QrCode currentCard={currentCard} loading={loading} setOpen={setEditQrOpen} />
                    </div>
                    <div className="w-full max-w-4xl mx-auto">
                        <CardCarousel
                            setCurrentCard={setCurrentCard}
                            cardData={cards}
                            loading={loading}
                        />
                    </div>
                </div>

                {/* ACTIONS SIDEBAR */}
                <div className="hidden lg:flex lg:fixed right-12 top-24 flex-col space-y-4 lg:col-span-4 xl:col-span-3 2xl:w-96">
                    <div className="bg-white rounded-xl shadow p-4 space-y-3">
                        <h3 className="text-lg font-bold text-gray-900 font-inter">Actions</h3>
                        <button
                            onClick={() => setEditQrOpen(true)}
                            className="w-full bg-primary-500 text-white py-2 rounded-lg font-semibold shadow cursor-pointer hover:bg-primary-900 transition-colors flex items-center justify-center gap-2"
                        >
                            <FaPaperPlane />
                            Share card
                        </button>
                        <Link
                            to={`/editor/${currentCard.id || ''}`}
                            className="w-full bg-gray-100 text-gray-800 py-2 rounded-lg font-semibold cursor-pointer hover:bg-gray-200 transition-colors text-center block"
                        >
                            Edit card
                        </Link>
                        <Link
                            to={`/editor`}
                            className="w-full inline-block text-center bg-gray-100 text-gray-800 py-2 rounded-lg font-semibold cursor-pointer hover:bg-gray-200 transition-colors"
                        >
                            Create new card
                        </Link>
                        <Link
                            to="/analytics"
                            className="w-full inline-block text-center bg-gray-100 text-gray-800 py-2 rounded-lg font-semibold cursor-pointer hover:bg-gray-200 transition-colors"
                        >
                            View analytics
                        </Link>
                    </div>
                    <div className="bg-white rounded-xl shadow p-4 space-y-2">
                        <h3 className="text-lg font-bold text-gray-900 font-inter">Current card</h3>
                        <p className="text-base font-semibold text-gray-800">{currentCard.name}</p>
                        <p className="text-sm text-gray-600">
                            {currentCard.items?.length || 0} items • Color {currentCard.color}
                        </p>
                    </div>
                </div>
                {/* END ACTIONS SIDEBAR */}

            </div>

            <button
                className="fixed lg:hidden z-20 bottom-24 right-1/2 translate-x-1/2 py-2 px-4 rounded-full flex items-center gap-2 bg-primary-500 shadow-lg cursor-pointer transition-all hover:-translate-y-1 hover:bg-primary-900 duration-200 ease-in-out"
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
