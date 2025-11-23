import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import Card from '@/features/editor/components/Card';
import type { CardType } from '@/types/card';

const baseCard: CardType = {
    id: 1,
    name: 'Sample Card',
    items: [
        {
            id: 11,
            type: 'name',
            value: 'John Doe',
            position: 1,
        },
    ],
};

const renderCard = (card: CardType, itemErrors?: Record<string, string>) =>
    render(
        <MemoryRouter initialEntries={['/editor/1']}>
            <Routes>
                <Route
                    path="/editor/:id"
                    element={
                        <Card
                            card={card}
                            setOpen={() => {}}
                            setCard={() => {}}
                            loading={false}
                            itemErrors={itemErrors}
                        />
                    }
                />
            </Routes>
        </MemoryRouter>
    );

describe('Editor Card component', () => {
    it('renders name item value', () => {
        renderCard(baseCard);
        expect(screen.getByText('John Doe')).toBeInTheDocument();
    });

    it('shows validation error and red ring when item has an error', () => {
        renderCard(baseCard, { 11: 'Name item empty' });

        expect(screen.getByText('Name item empty')).toBeInTheDocument();

        const ringContainer = screen.getByText('John Doe').closest('[class*="ring-red-500"]');
        expect(ringContainer?.className).toContain('ring-red-500');
    });
});
