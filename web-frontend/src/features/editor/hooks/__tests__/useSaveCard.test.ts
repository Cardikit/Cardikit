import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useSaveCard } from '@/features/editor/hooks/useSaveCard';
import { cardService } from '@/services/cardService';

vi.mock('@/services/cardService', () => ({
    cardService: {
        create: vi.fn(),
        update: vi.fn(),
    }
}));

const mockedCreate = vi.mocked(cardService.create);
const mockedUpdate = vi.mocked(cardService.update);

const baseCard = {
    id: 1,
    name: 'My Card',
    color: '#1D4ED8',
    theme: 'default',
    banner_image: null,
    avatar_image: null,
    items: [
        { id: 10, type: 'name', label: 'Name', value: 'John Doe', position: 0, meta: null },
    ],
};

describe('useSaveCard', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('validates and calls create for new card', async () => {
        mockedCreate.mockResolvedValueOnce(baseCard as any);

        const { result } = renderHook(() => useSaveCard());
        const setFormError = vi.fn();
        const setItemErrors = vi.fn();

        await act(async () => {
            await result.current.save({
                card: { ...baseCard, name: '  My Card  ', items: baseCard.items },
                setFormError,
                setItemErrors,
            });
        });

        expect(mockedCreate).toHaveBeenCalledTimes(1);
        expect(setFormError).toHaveBeenCalledWith(null);
        expect(setItemErrors).toHaveBeenCalledWith({});
    });

    it('maps item validation errors back to setItemErrors', async () => {
        const { result } = renderHook(() => useSaveCard());
        const setFormError = vi.fn();
        const setItemErrors = vi.fn();

        await act(async () => {
            await result.current.save({
                card: { ...baseCard, items: [{ id: 1, type: 'name', label: 'Name', value: '', position: 0 }] },
                setFormError,
                setItemErrors,
            });
        });

        expect(setItemErrors).toHaveBeenCalled();
        const map = setItemErrors.mock.calls[setItemErrors.mock.calls.length - 1][0];
        const firstMessage = Object.values(map)[0] as string | undefined;
        expect(firstMessage ?? '').toContain('empty');
    });

    it('calls update when cardId provided', async () => {
        mockedUpdate.mockResolvedValueOnce({ ...baseCard, name: 'Updated' } as any);

        const { result } = renderHook(() => useSaveCard());
        const setFormError = vi.fn();
        const setItemErrors = vi.fn();

        await act(async () => {
            await result.current.save({
                card: baseCard,
                cardId: 1,
                setFormError,
                setItemErrors,
            });
        });

        expect(mockedUpdate).toHaveBeenCalledTimes(1);
    });
});
