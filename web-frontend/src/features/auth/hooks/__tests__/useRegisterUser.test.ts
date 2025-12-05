import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useRegisterUser } from '@/features/auth/hooks/useRegisterUser';
import { authService } from '@/services/authService';
import { ApiError } from '@/services/httpClient';

vi.mock('@/services/authService', () => ({
    authService: {
        register: vi.fn(),
    }
}));

const mockedRegister = vi.mocked(authService.register);

describe('useRegister', () => {
    beforeEach(() => {
        mockedRegister.mockReset();
    });

    // Test Case 1: Successful Registration
    it('should successfully register a user and update states', async () => {
        const mockResponseData = { message: 'Registration successful', token: 'fake-token' };
        mockedRegister.mockResolvedValueOnce(mockResponseData as any);

        const { result } = renderHook(() => useRegisterUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        let returnedData: any;

        await act(async () => {
            returnedData = await result.current.register({ name: 'John Doe', email: 'test@example.com', password: 'password123' });
        });

        expect(mockedRegister).toHaveBeenCalledTimes(1);
        expect(mockedRegister).toHaveBeenCalledWith({
            name: 'John Doe',
            email: 'test@example.com',
            password: 'password123',
        });
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();
        expect(returnedData).toEqual(mockResponseData);
    });

    // Test Case 2: Failed Registration (Axios Error with specific backend message)
    it('should handle Axios errors with a specific backend message', async () => {
        const mockErrorMessage = 'User with this email already exists.';
        const mockError = new ApiError(mockErrorMessage, 422, { error: mockErrorMessage });
        mockedRegister.mockRejectedValueOnce(mockError);

        const { result } = renderHook(() => useRegisterUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.register({ name: 'John Doe', email: 'taken@example.com', password: 'password' }))
                .rejects.toThrow();
        });

        expect(mockedRegister).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe(mockErrorMessage);
    });

    // Test Case 3: Failed Register (Axios Error without specific backend error message)
    it('should handle Axios errors without a specific backend message', async () => {
        mockedRegister.mockRejectedValueOnce(new ApiError('Network Error', 500));

        const { result } = renderHook(() => useRegisterUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.register({ name: 'John Doe', email: 'server@example.com', password: 'pass' }))
                .rejects.toThrow();
        });

        expect(mockedRegister).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('Network Error');
    });

    // Test Case 4: Failed Login (Non-Axios Error)
    it('should handle non-Axios errors', async () => {
        const mockGenericError = new Error('Something truly unexpected happened!');
        mockedRegister.mockRejectedValueOnce(mockGenericError);

        const { result } = renderHook(() => useRegisterUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.register({ name: 'John Doe', email: 'fail@example.com', password: 'pass' }))
                .rejects.toThrow(mockGenericError);
        });

        expect(mockedRegister).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('Something truly unexpected happened!');
    });

    // Test Case 5: Error state is cleared on subsequent calls
    it('should clear previous errors on new register attempt', async () => {
        const mockErrorMessage = 'Initial error';
        mockedRegister.mockRejectedValueOnce(new ApiError(mockErrorMessage, 422, { error: mockErrorMessage })); // First call fails

        const { result } = renderHook(() => useRegisterUser());

        // First call to set an error
        await act(async () => {
            await expect(result.current.register({ name: 'John Doe', email: 'taken@example.com', password: 'password' })).rejects.toThrow();
        });
        expect(result.current.error).toBe(mockErrorMessage);
        expect(result.current.loading).toBe(false);

        // Mock the second call to succeed
        const mockSuccessResponse = { message: 'Registration successful' };
        mockedRegister.mockResolvedValueOnce(mockSuccessResponse as any);

        // Second call (success this time)
        await act(async () => {
            await result.current.register({ name: 'John Doe', email: 'available@example.com', password: 'password' });
        });

        expect(result.current.error).toBeNull(); // Error should be cleared
        expect(result.current.loading).toBe(false);
        expect(mockedRegister).toHaveBeenCalledTimes(2); // Called twice
    });
});
