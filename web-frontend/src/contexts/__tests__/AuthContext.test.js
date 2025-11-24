import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { describe, it, expect, vi, afterEach } from 'vitest';
import { render, screen, cleanup } from '@testing-library/react';
import { AuthProvider, useAuth } from '@/contexts/AuthContext';
const mockUser = {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
};
vi.mock('@/hooks/useAuthenticatedUser', () => ({
    useAuthenticatedUser: vi.fn(),
}));
import { useAuthenticatedUser } from '@/hooks/useAuthenticatedUser';
const TestComponent = () => {
    const { user, loading } = useAuth();
    return (_jsxs("div", { children: [_jsx("p", { "data-testid": "user", children: user ? user.name : 'No user' }), _jsx("p", { "data-testid": "loading", children: loading ? 'Loading' : 'Not loading' })] }));
};
describe('AuthContext', () => {
    const mockUseAuthenticatedUser = useAuthenticatedUser;
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
        render(_jsx(AuthProvider, { children: _jsx(TestComponent, {}) }));
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
        render(_jsx(AuthProvider, { children: _jsx(TestComponent, {}) }));
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
        render(_jsx(AuthProvider, { children: _jsx(TestComponent, {}) }));
        expect(screen.getByTestId('user').textContent).toBe('No user');
        expect(screen.getByTestId('loading').textContent).toBe('Not loading');
    });
});
