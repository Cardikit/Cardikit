import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useAuthenticatedUser } from '@/hooks/useAuthenticatedUser';
import api from '@/lib/axios';
// Mock the API client
vi.mock('@/lib/axios', () => ({
    default: {
        get: vi.fn()
    }
}));
const mockedApiGet = vi.mocked(api.get);
describe('useAuthenticatedUser', () => {
    const mockUser = {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
    };
    beforeEach(() => {
        vi.clearAllMocks();
    });
    // ✅ 1. Successful fetch on mount
    it('fetches and sets user on mount', async () => {
        mockedApiGet.mockResolvedValueOnce({ data: mockUser });
        const { result } = renderHook(() => useAuthenticatedUser());
        await act(async () => { });
        expect(mockedApiGet).toHaveBeenCalledWith('/@me');
        expect(result.current.user).toEqual(mockUser);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();
    });
    // ❌ 2. Failed fetch on mount
    it('sets error on fetch failure', async () => {
        mockedApiGet.mockRejectedValueOnce({
            response: { data: { message: 'Unauthorized' } }
        });
        const { result } = renderHook(() => useAuthenticatedUser());
        await act(async () => { });
        expect(result.current.user).toBeNull();
        expect(result.current.error).toBe('Unauthorized');
        expect(result.current.loading).toBe(false);
    });
    // 🌀 3. Manual refresh fetches user again
    it('fetches user when refresh is called', async () => {
        mockedApiGet.mockResolvedValueOnce({ data: mockUser });
        const { result } = renderHook(() => useAuthenticatedUser());
        // First render
        await act(async () => { });
        expect(result.current.user).toEqual(mockUser);
        // Update mock for second call
        const updatedUser = { ...mockUser, name: 'Jane Doe' };
        mockedApiGet.mockResolvedValueOnce({ data: updatedUser });
        // Refresh manually
        await act(async () => {
            await result.current.refresh();
        });
        expect(mockedApiGet).toHaveBeenCalledTimes(2);
        expect(result.current.user).toEqual(updatedUser);
        expect(result.current.loading).toBe(false);
    });
    // 🔥 4. Gracefully handles refresh failure
    it('sets error on refresh failure', async () => {
        mockedApiGet.mockResolvedValueOnce({ data: mockUser }); // First call succeeds
        const { result } = renderHook(() => useAuthenticatedUser());
        await act(async () => { });
        // Now fail on refresh
        mockedApiGet.mockRejectedValueOnce({
            response: { data: { message: 'Session expired' } }
        });
        await act(async () => {
            await result.current.refresh();
        });
        expect(result.current.user).toBeNull();
        expect(result.current.error).toBe('Session expired');
        expect(result.current.loading).toBe(false);
    });
});
