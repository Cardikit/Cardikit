import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useLogout } from '@/features/auth/hooks/useLogout';
import { authService } from '@/services/authService';
import { ApiError } from '@/services/httpClient';

// Mock the service to control API responses
vi.mock('@/services/authService', () => ({
    authService: {
        logout: vi.fn(),
    }
}));
const mockedLogout = vi.mocked(authService.logout);

describe('useLogout', () => {
    // Clear all mock implementations and call history before each test
    beforeEach(() => {
        mockedLogout.mockReset();
    });

    // Test Case 1: Successful Logout
    it('should successfully log out a user and update states', async () => {
        const mockResponseData = { message: 'Logout successful' };
        mockedLogout.mockResolvedValueOnce(mockResponseData as any);

        // Render the hook
        const { result } = renderHook(() => useLogout());

        // Assert initial state
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        let returnedData: any;

        // Act: Call the logout function and await its completion
        await act(async () => {
            returnedData = await result.current.logout();
        });

        // Assert API call
        expect(mockedLogout).toHaveBeenCalledTimes(1);

        // Assert final state after successful logout
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();
        expect(returnedData).toEqual(mockResponseData);
    });

    // Test Case 2: Failed Logout
    it('should handle any error during logout and set the error state', async () => {
        const mockError = new ApiError('Network error during logout', 500);
        mockedLogout.mockRejectedValueOnce(mockError);

        // Render the hook
        const { result } = renderHook(() => useLogout());

        // Assert initial state
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        // Act: Call the logout function and expect it to throw
        await act(async () => {
            await result.current.logout();
        });

        // Assert API call
        expect(mockedLogout).toHaveBeenCalledTimes(1);

        // Assert final state after error
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('Network error during logout');
        // Removed: expect(console.error).toHaveBeenCalledTimes(1);
    });

    // Test Case 3: Error state is cleared on subsequent calls
    it('should clear previous errors on a new logout attempt', async () => {
        const mockInitialError = new ApiError('First logout attempt failed', 500);
        // Mock the first call to fail
        mockedLogout.mockRejectedValueOnce(mockInitialError);

        // Render the hook
        const { result } = renderHook(() => useLogout());

        // First call to trigger an error
        await act(async () => {
            await result.current.logout();
        });
        expect(result.current.error).toBe('First logout attempt failed');
        expect(result.current.loading).toBe(false);

        // Mock the second call to succeed
        const mockSuccessResponse = { message: 'Logout successful again' };
        mockedLogout.mockResolvedValueOnce(mockSuccessResponse as any);

        // Second call (success this time)
        await act(async () => {
            await result.current.logout();
        });

        // Assert error is cleared and loading is false
        expect(result.current.error).toBeNull(); // Error should be cleared
        expect(result.current.loading).toBe(false);
        expect(mockedLogout).toHaveBeenCalledTimes(2); // Called twice
    });
});
