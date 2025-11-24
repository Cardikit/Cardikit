import { jsx as _jsx } from "react/jsx-runtime";
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, waitFor } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import Dashboard from '@/features/dashboard/pages/Dashboard';
import { useFetchCards } from '@/features/dashboard/hooks/useFetchCards';
import React from 'react';
vi.mock('@/features/dashboard/hooks/useFetchCards');
// Simplify child components to focus on page wiring/behavior
vi.mock('@/features/dashboard/components/CardCarousel', () => ({
    __esModule: true,
    default: ({ cardData, setCurrentCard, loading }) => {
        React.useEffect(() => {
            if (!loading && cardData.length) {
                setCurrentCard(cardData[0]);
            }
        }, [cardData, loading, setCurrentCard]);
        return _jsx("div", { "data-testid": "card-carousel", children: loading ? 'loading-cards' : `cards:${cardData.length}` });
    },
}));
vi.mock('@/features/dashboard/components/NavMenu', () => ({
    __esModule: true,
    default: ({ open }) => _jsx("div", { "data-testid": "nav-menu", children: open ? 'open' : 'closed' }),
}));
vi.mock('@/features/dashboard/components/BottomNav', () => ({
    __esModule: true,
    default: () => _jsx("div", { "data-testid": "bottom-nav" }),
}));
const mockedUseFetchCards = vi.mocked(useFetchCards);
const renderDashboard = () => render(_jsx(BrowserRouter, { children: _jsx(Dashboard, {}) }));
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
