import { describe, it, expect, vi, afterEach, type Mock } from 'vitest';
import { render, screen, cleanup } from '@testing-library/react';
import { AuthProvider, useAuth } from '@/contexts/AuthContext';
import type { User } from '@/types/user';

const mockUser: User = {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
}

vi.mock('@/hooks/useAuthenticatedUser', () => ({
    useAuthenticatedUser: vi.fn(),
}));

import { useAuthenticatedUser } from '@/hooks/useAuthenticatedUser';

const TestComponent: React.FC = () => {
    const { user, loading } = useAuth();
    return (
        <div>
            <p data-testid="user">{user ? user.name : 'No user'}</p>
            <p data-testid="loading">{loading ? 'Loading' : 'Not loading'}</p>
        </div>
    );
};

describe('AuthContext', () => {
    const mockUseAuthenticatedUser = useAuthenticatedUser as Mock;

    afterEach(() => {
        cleanup();
    });

    it('provides user and loading state to children', () => {
        mockUseAuthenticatedUser.mockReturnValue({
            user: mockUser,
            loading: false,
            refresh: vi.fn(),
            error: null,
        });

        render(
            <AuthProvider>
                <TestComponent />
            </AuthProvider>
        );

        expect(screen.getByTestId('user').textContent).toBe('John Doe');
        expect(screen.getByTestId('loading').textContent).toBe('Not loading');
    });

    it('provides null user and loading=true during initial load', () => {
        mockUseAuthenticatedUser.mockReturnValue({
            user: null,
            loading: true,
            refresh: vi.fn(),
            error: null,
        });

        render(
            <AuthProvider>
                <TestComponent />
            </AuthProvider>
        );

        expect(screen.getByTestId('user').textContent).toBe('No user');
        expect(screen.getByTestId('loading').textContent).toBe('Loading');
    });

    it('handles error state', () => {
        mockUseAuthenticatedUser.mockReturnValue({
            user: null,
            loading: false,
            refresh: vi.fn(),
            error: new Error('Failed to load user'),
        });

        render(
            <AuthProvider>
                <TestComponent />
            </AuthProvider>
        );

        expect(screen.getByTestId('user').textContent).toBe('No user');
        expect(screen.getByTestId('loading').textContent).toBe('Not loading');
    });
});
