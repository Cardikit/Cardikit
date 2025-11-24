import { jsx as _jsx } from "react/jsx-runtime";
import { describe, it, expect } from 'vitest';
import { render, screen } from '@testing-library/react';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import Card from '@/features/editor/components/Card';
const baseCard = {
    id: 1,
    name: 'Sample Card',
    color: '#1D4ED8',
    banner_image: null,
    avatar_image: null,
    items: [
        {
            id: 11,
            type: 'name',
            value: 'John Doe',
            position: 1,
        },
    ],
};
const renderCard = (card, itemErrors) => render(_jsx(MemoryRouter, { initialEntries: ['/editor/1'], children: _jsx(Routes, { children: _jsx(Route, { path: "/editor/:id", element: _jsx(Card, { card: card, setOpen: () => { }, setCard: () => { }, loading: false, onOpenBanner: () => { }, onOpenAvatar: () => { }, itemErrors: itemErrors }) }) }) }));
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
