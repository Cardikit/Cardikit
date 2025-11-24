import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { describe, it, expect, vi } from 'vitest';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import { render, screen } from '@testing-library/react';
import PrivateRoute from '@/routes/PrivateRoute';
import { useAuth } from '@/contexts/AuthContext';
vi.mock('@/contexts/AuthContext', () => ({
    useAuth: vi.fn(),
}));
vi.mock('@/components/Loading', () => ({
    default: () => _jsx("div", { children: "Loading..." }),
}));
const ProtectedPage = () => _jsx("div", { children: "Protected Content" });
const LoginPage = () => _jsx("div", { children: "Login Page" });
describe('PrivateRoute', () => {
    it('shows loading screen when auth is loading', () => {
        useAuth.mockReturnValue({ user: null, loading: true });
        render(_jsx(MemoryRouter, { initialEntries: ['/dashboard'], children: _jsx(Routes, { children: _jsx(Route, { element: _jsx(PrivateRoute, {}), children: _jsx(Route, { path: "/dashboard", element: _jsx(ProtectedPage, {}) }) }) }) }));
        expect(screen.getByText('Loading...')).toBeInTheDocument();
    });
    it('renders protected content if user is authenticated', () => {
        useAuth.mockReturnValue({
            user: { id: 1, name: 'Test User' },
            loading: false,
        });
        render(_jsx(MemoryRouter, { initialEntries: ['/dashboard'], children: _jsx(Routes, { children: _jsx(Route, { element: _jsx(PrivateRoute, {}), children: _jsx(Route, { path: "/dashboard", element: _jsx(ProtectedPage, {}) }) }) }) }));
        expect(screen.getByText('Protected Content')).toBeInTheDocument();
    });
    it('redirects unauthenticated user to login', () => {
        useAuth.mockReturnValue({ user: null, loading: false });
        render(_jsx(MemoryRouter, { initialEntries: ['/dashboard'], children: _jsxs(Routes, { children: [_jsx(Route, { path: "/login", element: _jsx(LoginPage, {}) }), _jsx(Route, { element: _jsx(PrivateRoute, {}), children: _jsx(Route, { path: "/dashboard", element: _jsx(ProtectedPage, {}) }) })] }) }));
        expect(screen.getByText('Login Page')).toBeInTheDocument();
    });
});
