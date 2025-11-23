import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, waitFor } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import Dashboard from '@/features/dashboard/pages/Dashboard';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import type { CardType } from '@/types/card';
import React from 'react';

vi.mock('@/features/dashboard/hooks/useFetchCards');

// Simplify child components to focus on page wiring/behavior
vi.mock('@/features/dashboard/components/CardCarousel', () => ({
    __esModule: true,
    default: ({ cardData, setCurrentCard, loading }: { cardData: CardType[]; setCurrentCard: (card: CardType) => void; loading: boolean; }) => {
        React.useEffect(() => {
            if (!loading && cardData.length) {
                setCurrentCard(cardData[0]);
            }
        }, [cardData, loading, setCurrentCard]);

        return <div data-testid="card-carousel">{loading ? 'loading-cards' : `cards:${cardData.length}`}</div>;
    },
}));

vi.mock('@/features/dashboard/components/NavMenu', () => ({
    __esModule: true,
    default: ({ open }: { open: boolean }) => <div data-testid="nav-menu">{open ? 'open' : 'closed'}</div>,
}));

vi.mock('@/features/dashboard/components/BottomNav', () => ({
    __esModule: true,
    default: () => <div data-testid="bottom-nav" />,
}));

const mockedUseFetchCards = vi.mocked(useFetchCards);

const renderDashboard = () =>
    render(
        <BrowserRouter>
            <Dashboard />
        </BrowserRouter>
    );

describe('Dashboard page', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('shows loading state while cards are fetching', () => {
        mockedUseFetchCards.mockReturnValue({
            cards: [],
            loading: true,
            error: null,
            refresh: vi.fn(),
        });

        renderDashboard();

        expect(screen.getByText(/Fetching Cards/i)).toBeInTheDocument();
        expect(screen.getByTestId('card-carousel')).toHaveTextContent('loading-cards');
    });

    it('displays first card name once cards load and passes cards to carousel', async () => {
        mockedUseFetchCards.mockReturnValue({
            cards: [
                { id: 1, name: 'Personal Card', items: [] },
                { id: 2, name: 'Work Card', items: [] },
            ],
            loading: false,
            error: null,
            refresh: vi.fn(),
        });

        renderDashboard();

        await waitFor(() => {
            expect(screen.getByText('Personal Card')).toBeInTheDocument();
        });

        expect(screen.getByTestId('card-carousel')).toHaveTextContent('cards:2');
    });
});
