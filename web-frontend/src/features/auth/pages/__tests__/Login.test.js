import { jsx as _jsx } from "react/jsx-runtime";
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import Login from '@/features/auth/pages/Login';
import { BrowserRouter } from 'react-router-dom';
import { useLoginUser } from '@/features/auth/hooks/useLoginUser';
import { useAuth } from '@/contexts/AuthContext';
// ⛔ mock login + auth context hooks
vi.mock('@/features/auth/hooks/useLoginUser');
vi.mock('@/contexts/AuthContext');
// 🧪 Mock return values setup
const mockLogin = vi.fn();
const mockRefresh = vi.fn();
beforeEach(() => {
    vi.clearAllMocks();
    const mockedUseLoginUser = vi.mocked(useLoginUser);
    mockedUseLoginUser.mockReturnValue({
        login: mockLogin,
        loading: false,
        error: null,
    });
    const mockedUseAuth = vi.mocked(useAuth);
    mockedUseAuth.mockReturnValue({
        refresh: mockRefresh,
        user: null,
        loading: false,
    });
});
// Wrap with router context
const renderLogin = () => render(_jsx(BrowserRouter, { children: _jsx(Login, {}) }));
describe('Login Page', () => {
    // Test 1: Renders component
    it('renders inputs and submit button', () => {
        renderLogin();
        expect(screen.getByPlaceholderText('Enter your email')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Enter your password')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /sign in/i })).toBeInTheDocument();
    });
    // Test 2: Form validation
    it('validates required fields', async () => {
        renderLogin();
        fireEvent.click(screen.getByRole('button', { name: /sign in/i }));
        await waitFor(() => {
            expect(screen.getByText(/Email is required/i)).toBeInTheDocument();
            expect(screen.getByText(/Password is required/i)).toBeInTheDocument();
        });
    });
    // Test 3: Form submission
    it('calls login and refresh on valid submit', async () => {
        renderLogin();
        fireEvent.change(screen.getByPlaceholderText('Enter your email'), {
            target: { value: 'test@example.com' },
        });
        fireEvent.change(screen.getByPlaceholderText('Enter your password'), {
            target: { value: 'password123' },
        });
        fireEvent.click(screen.getByRole('button', { name: /sign in/i }));
        await waitFor(() => {
            expect(mockLogin).toHaveBeenCalledWith({
                email: 'test@example.com',
                password: 'password123',
            });
            expect(mockRefresh).toHaveBeenCalled();
        });
    });
    // Test 4: Displays login error
    it('displays login error if present', () => {
        const mockedUseLoginUser = vi.mocked(useLoginUser);
        mockedUseLoginUser.mockReturnValue({
            login: vi.fn(),
            loading: false,
            error: 'Invalid credentials',
        });
        renderLogin();
        expect(screen.getByText(/invalid credentials/i)).toBeInTheDocument();
    });
});
