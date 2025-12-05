import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import TopNav from '@/features/editor/components/TopNav';
import type { CardType } from '@/types/card';

const saveMock = vi.fn();
const useSaveCardMock = vi.fn();

vi.mock('@/features/editor/hooks/useSaveCard', () => ({
    useSaveCard: () => useSaveCardMock(),
}));

const baseCard: CardType = {
    id: 1,
    name: 'Sample Card',
    color: '#1D4ED8',
    banner_image: null,
    avatar_image: null,
    items: [],
};

const renderTopNav = (route = '/editor/5', saveDisabled = false) => {
    useSaveCardMock.mockReturnValue({
        save: saveMock,
        saving: saveDisabled,
        error: null,
    });

    const setOpen = vi.fn();
    const setFormError = vi.fn();
    const setItemErrors = vi.fn();

    const ui = render(
        <MemoryRouter initialEntries={[route]}>
            <Routes>
                <Route
                    path="/editor/:id"
                    element={
                        <TopNav
                            card={baseCard}
                            setOpen={setOpen}
                            formError={null}
                            setFormError={setFormError}
                            setItemErrors={setItemErrors}
                        />
                    }
                />
            </Routes>
        </MemoryRouter>
    );

    return { setOpen, setFormError, setItemErrors, ...ui };
};

describe('TopNav', () => {
    beforeEach(() => {
        vi.resetModules();
        vi.clearAllMocks();
    });

    it('calls save with cardId from route on save click', () => {
        renderTopNav('/editor/10');

        const saveButton = screen.getByText(/Save/i);
        fireEvent.click(saveButton);

        expect(saveMock).toHaveBeenCalledTimes(1);
        const args = saveMock.mock.calls[0][0];
        expect(args.cardId).toBe(10);
        expect(args.card.name).toBe('Sample Card');
    });

    it('does not trigger save when saving flag is true', () => {
        renderTopNav('/editor/10', true);

        const saveButton = screen.getByText(/Saving\.\.\./i);
        fireEvent.click(saveButton);

        expect(saveMock).not.toHaveBeenCalled();
    });
});
