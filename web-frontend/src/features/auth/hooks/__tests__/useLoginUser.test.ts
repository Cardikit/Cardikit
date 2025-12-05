import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useLoginUser } from '@/features/auth/hooks/useLoginUser';
import { authService } from '@/services/authService';
import { ApiError } from '@/services/httpClient';

vi.mock('@/services/authService', () => ({
    authService: {
        login: vi.fn(),
    }
}));

const mockedLogin = vi.mocked(authService.login);

describe('useLoginUser', () => {
    beforeEach(() => {
        mockedLogin.mockReset();
    });

    // Test Case 1: Successful Login
    it('should successfully log in a user and update states', async () => {
        const mockResponseData = { message: 'Login successful', token: 'fake-token' };
        mockedLogin.mockResolvedValueOnce(mockResponseData as any);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        let returnedData: any;

        await act(async () => {
            returnedData = await result.current.login({ email: 'test@example.com', password: 'password123' });
        });

        expect(mockedLogin).toHaveBeenCalledTimes(1);
        expect(mockedLogin).toHaveBeenCalledWith({
            email: 'test@example.com',
            password: 'password123',
        });
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();
        expect(returnedData).toEqual(mockResponseData);
    });

    // Test Case 2: Failed Login (Axios Error with specific backend message)
    it('should handle Axios errors with a specific backend message', async () => {
        const mockErrorMessage = 'Invalid credentials provided.';
        const mockError = new ApiError('Invalid credentials provided.', 401, { error: mockErrorMessage });
        mockedLogin.mockRejectedValueOnce(mockError);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.login({ email: 'wrong@example.com', password: 'badpassword' }))
                .rejects.toThrow();
        });

        expect(mockedLogin).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe(mockErrorMessage);
    });

    // Test Case 3: Failed Login (Axios Error without specific backend error message)
    it('should handle Axios errors without a specific backend message', async () => {
        mockedLogin.mockRejectedValueOnce(new ApiError('Network Error', 500));

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.login({ email: 'server@example.com', password: 'pass' }))
                .rejects.toThrow();
        });

        expect(mockedLogin).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('Network Error');
    });

    // Test Case 4: Failed Login (Non-Axios Error)
    it('should handle non-Axios errors', async () => {
        const mockGenericError = new Error('Something truly unexpected happened!');
        mockedLogin.mockRejectedValueOnce(mockGenericError);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.login({ email: 'fail@example.com', password: 'pass' }))
                .rejects.toThrow(mockGenericError);
        });

        expect(mockedLogin).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('Something truly unexpected happened!');
    });

    // Test Case 5: Error state is cleared on subsequent calls
    it('should clear previous errors on new login attempt', async () => {
        const mockErrorMessage = 'Initial error';
        mockedLogin.mockRejectedValueOnce(new ApiError(mockErrorMessage, 401, { error: mockErrorMessage })); // First call fails

        const { result } = renderHook(() => useLoginUser());

        // First call to set an error
        await act(async () => {
            await expect(result.current.login({ email: 'wrong@example.com', password: 'badpassword' })).rejects.toThrow();
        });
        expect(result.current.error).toBe(mockErrorMessage);
        expect(result.current.loading).toBe(false);

        // Mock the second call to succeed
        const mockSuccessResponse = { message: 'Login successful' };
        mockedLogin.mockResolvedValueOnce(mockSuccessResponse as any);

        // Second call (success this time)
        await act(async () => {
            await result.current.login({ email: 'correct@example.com', password: 'correctpassword' });
        });

        expect(result.current.error).toBeNull(); // Error should be cleared
        expect(result.current.loading).toBe(false);
        expect(mockedLogin).toHaveBeenCalledTimes(2); // Called twice
    });
});
