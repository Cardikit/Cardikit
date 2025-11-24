import { jsx as _jsx } from "react/jsx-runtime";
import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import Input from '../Input'; // Adjust the import path as needed
import { IoMdMail } from 'react-icons/io'; // Example icon for startAdornment
describe('Input Component', () => {
    // Test Case 1: Basic rendering
    it('renders correctly with default props', () => {
        render(_jsx(Input, { placeholder: "Enter text" }));
        const inputElement = screen.getByPlaceholderText('Enter text');
        expect(inputElement).toBeInTheDocument();
        expect(inputElement).toHaveAttribute('type', 'text'); // Default type
        expect(screen.queryByTestId('error-message')).not.toBeInTheDocument();
        expect(screen.queryByTestId('start-adornment')).not.toBeInTheDocument();
        expect(screen.queryByLabelText('toggle password visibility')).not.toBeInTheDocument(); // No password toggle by default
    });
    // Test Case 2: Renders with startAdornment
    it('renders with a startAdornment', () => {
        render(_jsx(Input, { startAdornment: _jsx(IoMdMail, { "data-testid": "mail-icon" }) }));
        const mailIcon = screen.getByTestId('mail-icon');
        expect(mailIcon).toBeInTheDocument();
        const inputElement = screen.getByRole('textbox');
        expect(inputElement).toHaveClass('pl-12'); // Check for class application
    });
    // Test Case 3: Renders with error message
    it('renders an error message when error prop is provided', () => {
        render(_jsx(Input, { error: "This field is required" }));
        const errorMessage = screen.getByText('This field is required');
        expect(errorMessage).toBeInTheDocument();
        expect(errorMessage).toHaveClass('text-[#FA3C25]'); // Check error styling
    });
    // Test Case 4: Password input functionality
    it('toggles password visibility when eye icon is clicked', () => {
        render(_jsx(Input, { type: "password", placeholder: "Password" }));
        const passwordInput = screen.getByPlaceholderText('Password');
        const toggleButton = screen.getByRole('button');
        expect(passwordInput).toHaveAttribute('type', 'password');
        // Initially, expect the eye-on icon
        expect(screen.getByTestId('eye-on-icon')).toBeInTheDocument();
        expect(screen.queryByTestId('eye-off-icon')).not.toBeInTheDocument();
        // Click to show password
        fireEvent.click(toggleButton);
        expect(passwordInput).toHaveAttribute('type', 'text');
        // Now, expect the eye-off icon
        expect(screen.getByTestId('eye-off-icon')).toBeInTheDocument();
        expect(screen.queryByTestId('eye-on-icon')).not.toBeInTheDocument();
        // Click again to hide password
        fireEvent.click(toggleButton);
        expect(passwordInput).toHaveAttribute('type', 'password');
        // Back to eye-on icon
        expect(screen.getByTestId('eye-on-icon')).toBeInTheDocument();
        expect(screen.queryByTestId('eye-off-icon')).not.toBeInTheDocument();
    });
    // Test Case 5: Ensures other props are passed through
    it('passes through additional native input props', () => {
        const handleChange = vi.fn();
        render(_jsx(Input, { name: "my-input", id: "test-id", onChange: handleChange, value: "test-value" }));
        const inputElement = screen.getByDisplayValue('test-value');
        expect(inputElement).toHaveAttribute('name', 'my-input');
        expect(inputElement).toHaveAttribute('id', 'test-id');
        fireEvent.change(inputElement, { target: { value: 'new value' } });
        expect(handleChange).toHaveBeenCalledTimes(1);
    });
    // You might also add tests to ensure the `pr-12` class is applied for password inputs, etc.
});
