import { jsx as _jsx, jsxs as _jsxs } from "react/jsx-runtime";
import { describe, it, expect, vi } from 'vitest';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import { render, screen } from '@testing-library/react';
import GuestRoute from '@/routes/GuestRoute';
import { useAuth } from '@/contexts/AuthContext';
// Mock the useAuth hook
vi.mock('@/contexts/AuthContext', () => ({
    useAuth: vi.fn(),
}));
// Mock loading component
vi.mock('@/components/Loading', () => ({
    default: () => _jsx("div", { children: "Loading..." }),
}));
const TestPage = () => _jsx("div", { children: "Guest Page" });
const Dashboard = () => _jsx("div", { children: "Dashboard" });
describe('GuestRoute', () => {
    it('renders loading when auth is loading', () => {
        useAuth.mockReturnValue({
            user: null,
            loading: true,
        });
        render(_jsx(MemoryRouter, { initialEntries: ['/login'], children: _jsx(Routes, { children: _jsx(Route, { element: _jsx(GuestRoute, {}), children: _jsx(Route, { path: "/login", element: _jsx(TestPage, {}) }) }) }) }));
        expect(screen.getByText('Loading...')).toBeInTheDocument();
    });
    it('renders guest page when user is not authenticated', () => {
        useAuth.mockReturnValue({
            user: null,
            loading: false,
        });
        render(_jsx(MemoryRouter, { initialEntries: ['/login'], children: _jsx(Routes, { children: _jsx(Route, { element: _jsx(GuestRoute, {}), children: _jsx(Route, { path: "/login", element: _jsx(TestPage, {}) }) }) }) }));
        expect(screen.getByText('Guest Page')).toBeInTheDocument();
    });
    it('redirects authenticated user to dashboard', () => {
        useAuth.mockReturnValue({
            user: { id: 1, name: 'Test User' },
            loading: false,
        });
        render(_jsx(MemoryRouter, { initialEntries: ['/login'], children: _jsxs(Routes, { children: [_jsx(Route, { path: "/dashboard", element: _jsx(Dashboard, {}) }), _jsx(Route, { element: _jsx(GuestRoute, {}), children: _jsx(Route, { path: "/login", element: _jsx(TestPage, {}) }) })] }) }));
        expect(screen.getByText('Dashboard')).toBeInTheDocument();
    });
});
