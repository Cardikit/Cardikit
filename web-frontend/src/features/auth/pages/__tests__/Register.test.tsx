import { describe, it, expect, vi, beforeEach } from 'vitest';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import Register from '@/features/auth/pages/Register';
import { BrowserRouter } from 'react-router-dom';
import { useRegisterUser } from '@/features/auth/hooks/useRegisterUser';
import { useAuth } from '@/contexts/AuthContext';

// ⛔ mock register + auth context hooks
vi.mock('@/features/auth/hooks/useRegisterUser');
vi.mock('@/contexts/AuthContext');

// 🧪 Mock return values setup
const mockRegister = vi.fn();
const mockRefresh = vi.fn();

beforeEach(() => {
    vi.clearAllMocks();

    const mockedUseRegisterUser = vi.mocked(useRegisterUser);
    mockedUseRegisterUser.mockReturnValue({
        register: mockRegister,
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
const renderRegister = () =>
    render(
        <BrowserRouter>
            <Register />
        </BrowserRouter>
    );

describe('Register Page', () => {

    // Test 1: Renders component
    it('renders inputs and submit button', () => {
        renderRegister();

        expect(screen.getByPlaceholderText('Enter your name')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Enter your email')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Enter your password')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Confirm your password')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: /sign up/i })).toBeInTheDocument();
    });

    // Test 2: Form validation
    it('validates required fields', async () => {
        renderRegister();

        fireEvent.click(screen.getByRole('button', { name: /sign up/i }));

        await waitFor(() => {
            expect(screen.getByText(/Name is required/i)).toBeInTheDocument();
            expect(screen.getByText(/Email is required/i)).toBeInTheDocument();
            expect(screen.getByText(/Password is required/i)).toBeInTheDocument();
            expect(screen.getByText(/Terms must be accepted/i)).toBeInTheDocument();
        });
    });

    // Test 3: Form submission
    it('calls register and refresh on valid submit', async () => {
        renderRegister();

        fireEvent.change(screen.getByPlaceholderText('Enter your name'), {
            target: { value: 'John Doe' },
        });

        fireEvent.change(screen.getByPlaceholderText('Enter your email'), {
            target: { value: 'test@example.com' },
        });

        fireEvent.change(screen.getByPlaceholderText('Enter your password'), {
            target: { value: 'password123' },
        });

        fireEvent.change(screen.getByPlaceholderText('Confirm your password'), {
            target: { value: 'password123' },
        });

        fireEvent.click(screen.getByRole('checkbox'));

        fireEvent.click(screen.getByRole('button', { name: /sign up/i }));

        await waitFor(() => {
            expect(mockRegister).toHaveBeenCalledWith({
                name: 'John Doe',
                email: 'test@example.com',
                password: 'password123',
            });

            expect(mockRefresh).toHaveBeenCalled();
        });
    });

    // Test 4: Displays register error
    it('displays register error if present', () => {
        const mockedUseLoginUser = vi.mocked(useRegisterUser);
        mockedUseLoginUser.mockReturnValue({
            register: vi.fn(),
            loading: false,
            error: 'Email already exists',
        });

        renderRegister();

        expect(screen.getByText(/Email already exists/i)).toBeInTheDocument();
    });
});

