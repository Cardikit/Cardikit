import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useLoginUser } from '@/features/auth/hooks/useLoginUser';
import api from '@/lib/axios';

vi.mock('@/lib/axios', () => ({
    default: {
        post: vi.fn()
    }
}));

const mockedApiPost = vi.mocked(api.post);

describe('useLoginUser', () => {
    beforeEach(() => {
        mockedApiPost.mockReset();
    });

    // Test Case 1: Successful Login
    it('should successfully log in a user and update states', async () => {
        const mockResponseData = { message: 'Login successful', token: 'fake-token' };
        mockedApiPost.mockResolvedValueOnce({ data: mockResponseData, status: 200, statusText: 'OK', headers: {}, config: {} } as any);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        let returnedData: any;

        await act(async () => {
            returnedData = await result.current.login({ email: 'test@example.com', password: 'password123' });
        });

        expect(mockedApiPost).toHaveBeenCalledTimes(1);
        expect(mockedApiPost).toHaveBeenCalledWith('/login', {
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
        const mockAxiosError = {
            response: { data: { error: mockErrorMessage }, status: 401, statusText: 'Unauthorized', headers: {}, config: {} },
            isAxiosError: true,
            name: 'AxiosError',
            message: 'Request failed with status code 401',
            config: {},
            toJSON: () => ({}),
        };

        mockedApiPost.mockRejectedValueOnce(mockAxiosError as any);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.login({ email: 'wrong@example.com', password: 'badpassword' }))
                .rejects.toThrow();
        });

        expect(mockedApiPost).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe(mockErrorMessage);
    });

    // Test Case 3: Failed Login (Axios Error without specific backend error message)
    it('should handle Axios errors without a specific backend message', async () => {
        const mockAxiosError = {
            response: { status: 500, statusText: 'Internal Server Error', headers: {}, config: {} },
            isAxiosError: true,
            name: 'AxiosError',
            message: 'Network Error',
            config: {},
            toJSON: () => ({}),
        };

        mockedApiPost.mockRejectedValueOnce(mockAxiosError as any);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.login({ email: 'server@example.com', password: 'pass' }))
                .rejects.toThrow();
        });

        expect(mockedApiPost).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('An unknown API error occurred. Please try again.');
    });

    // Test Case 4: Failed Login (Non-Axios Error)
    it('should handle non-Axios errors', async () => {
        const mockGenericError = new Error('Something truly unexpected happened!');
        mockedApiPost.mockRejectedValueOnce(mockGenericError);

        const { result } = renderHook(() => useLoginUser());

        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBeNull();

        await act(async () => {
            await expect(result.current.login({ email: 'fail@example.com', password: 'pass' }))
                .rejects.toThrow(mockGenericError);
        });

        expect(mockedApiPost).toHaveBeenCalledTimes(1);
        expect(result.current.loading).toBe(false);
        expect(result.current.error).toBe('Unexpected error occurred');
    });

    // Test Case 5: Error state is cleared on subsequent calls
    it('should clear previous errors on new login attempt', async () => {
        const mockErrorMessage = 'Initial error';
        const mockAxiosError = {
            response: { data: { error: mockErrorMessage }, status: 401, statusText: 'Unauthorized', headers: {}, config: {} },
            isAxiosError: true,
            name: 'AxiosError',
            message: 'Request failed with status code 401',
            config: {},
            toJSON: () => ({})
        };

        mockedApiPost.mockRejectedValueOnce(mockAxiosError as any); // First call fails

        const { result } = renderHook(() => useLoginUser());

        // First call to set an error
        await act(async () => {
            await expect(result.current.login({ email: 'wrong@example.com', password: 'badpassword' })).rejects.toThrow();
        });
        expect(result.current.error).toBe(mockErrorMessage);
        expect(result.current.loading).toBe(false);

        // Mock the second call to succeed
        const mockSuccessResponse = { message: 'Login successful' };
        mockedApiPost.mockResolvedValueOnce({ data: mockSuccessResponse, status: 200, statusText: 'OK', headers: {}, config: {} } as any);

        // Second call (success this time)
        await act(async () => {
            await result.current.login({ email: 'correct@example.com', password: 'correctpassword' });
        });

        expect(result.current.error).toBeNull(); // Error should be cleared
        expect(result.current.loading).toBe(false);
        expect(mockedApiPost).toHaveBeenCalledTimes(2); // Called twice
    });
});
