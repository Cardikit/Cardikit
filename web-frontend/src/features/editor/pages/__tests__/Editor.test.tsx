import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { MemoryRouter, Route, Routes } from 'react-router-dom';
import Editor from '@/features/editor/pages/Editor';
import { useFetchCard } from '@/features/editor/hooks/useFetchCard';
import { useDeleteCard } from '@/features/editor/hooks/useDeleteCard';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';

vi.mock('@/features/editor/hooks/useFetchCard');
vi.mock('@/features/editor/hooks/useDeleteCard');
vi.mock('@/lib/fetchCsrfToken');

const mockedUseFetchCard = vi.mocked(useFetchCard);
const mockedUseDeleteCard = vi.mocked(useDeleteCard);
const mockedFetchCsrf = vi.mocked(fetchCsrfToken);

// Override navigate + params
const navigateMock = vi.fn();
vi.mock('react-router-dom', async () => {
    const actual = await vi.importActual<typeof import('react-router-dom')>('react-router-dom');
    return {
        ...actual,
        useNavigate: () => navigateMock,
        useParams: () => ({ id: '1' }),
    };
});

const renderEditor = () =>
    render(
        <MemoryRouter initialEntries={['/editor/1']}>
            <Routes>
                <Route path="/editor/:id" element={<Editor />} />
            </Routes>
        </MemoryRouter>
    );

describe('Editor page', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        mockedFetchCsrf.mockResolvedValue(undefined);
    });

    it('shows loading state when fetching card by id', () => {
        mockedUseFetchCard.mockReturnValue({
            card: { id: 1, name: 'Test Card', items: [] },
            setCard: vi.fn(),
            refresh: vi.fn(),
            loading: true,
            error: null,
        });
        mockedUseDeleteCard.mockReturnValue({ deleteCard: vi.fn(), loading: false, error: null });

        renderEditor();

        expect(screen.getByTestId('editor-skeleton')).toBeInTheDocument();
    });

    it('deletes card and navigates back to dashboard', async () => {
        const deleteCardMock = vi.fn().mockResolvedValue({});
        mockedUseFetchCard.mockReturnValue({
            card: { id: 1, name: 'Test Card', items: [] },
            setCard: vi.fn(),
            refresh: vi.fn(),
            loading: false,
            error: null,
        });
        mockedUseDeleteCard.mockReturnValue({ deleteCard: deleteCardMock, loading: false, error: null });

        renderEditor();

        // Open confirmation modal
        const [deleteButton] = screen.getAllByRole('button', { name: /delete card/i });
        fireEvent.click(deleteButton);
        // Confirm deletion
        fireEvent.click(screen.getByRole('button', { name: /^delete$/i }));

        await waitFor(() => {
            expect(mockedFetchCsrf).toHaveBeenCalled();
            expect(deleteCardMock).toHaveBeenCalledWith(1);
            expect(navigateMock).toHaveBeenCalledWith('/dashboard');
        });
    });
});
