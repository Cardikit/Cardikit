import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useAuthenticatedUser } from '@/hooks/useAuthenticatedUser';
import { authService } from '@/services/authService';
import type { User } from '@/types/user';
import { ApiError } from '@/services/httpClient';

// Mock the service
vi.mock('@/services/authService', () => ({
  authService: {
    me: vi.fn()
  }
}));

const mockedMe = vi.mocked(authService.me);

describe('useAuthenticatedUser', () => {
  const mockUser: User = {
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
    mockedMe.mockResolvedValueOnce(mockUser);

    const { result } = renderHook(() => useAuthenticatedUser());

    await act(async () => {});

    expect(mockedMe).toHaveBeenCalledWith();
    expect(result.current.user).toEqual(mockUser);
    expect(result.current.loading).toBe(false);
    expect(result.current.error).toBeNull();
  });

  // ❌ 2. Failed fetch on mount
  it('sets error on fetch failure', async () => {
    mockedMe.mockRejectedValueOnce(new ApiError('Unauthorized', 401));

    const { result } = renderHook(() => useAuthenticatedUser());

    await act(async () => {});

    expect(result.current.user).toBeNull();
    expect(result.current.error).toBe('Unauthorized');
    expect(result.current.loading).toBe(false);
  });

  // 🌀 3. Manual refresh fetches user again
  it('fetches user when refresh is called', async () => {
    mockedMe.mockResolvedValueOnce(mockUser);

    const { result } = renderHook(() => useAuthenticatedUser());

    // First render
    await act(async () => {});

    expect(result.current.user).toEqual(mockUser);

    // Update mock for second call
    const updatedUser = { ...mockUser, name: 'Jane Doe' };
    mockedMe.mockResolvedValueOnce(updatedUser);

    // Refresh manually
    await act(async () => {
      await result.current.refresh();
    });

    expect(mockedMe).toHaveBeenCalledTimes(2);
    expect(result.current.user).toEqual(updatedUser);
    expect(result.current.loading).toBe(false);
  });

  // 🔥 4. Gracefully handles refresh failure
  it('sets error on refresh failure', async () => {
    mockedMe.mockResolvedValueOnce(mockUser); // First call succeeds
    const { result } = renderHook(() => useAuthenticatedUser());
    await act(async () => {});

    // Now fail on refresh
    mockedMe.mockRejectedValueOnce(new ApiError('Session expired', 401));

    await act(async () => {
      await result.current.refresh();
    });

    expect(result.current.user).toBeNull();
    expect(result.current.error).toBe('Session expired');
    expect(result.current.loading).toBe(false);
  });
});
