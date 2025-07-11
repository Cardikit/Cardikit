import { describe, it, expect } from 'vitest';
import { registerSchema, loginSchema } from '@/features/auth/validationSchema';

describe('registerSchema', () => {
    const registerPayload = {
        name: 'John Doe',
        email: 'test@example.com',
        password: 'password123',
        confirmPassword: 'password123',
        acceptTerms: true
    }

    it('validates a correct payload', async () => {
        const result = await registerSchema.validate(registerPayload);
        expect(result).toBeTruthy();
    });

    it('rejects mismatched passwords', async () => {
        await expect(registerSchema.validate({
            ...registerPayload,
            password: 'password123',
            confirmPassword: 'password456'
        })).rejects.toThrow('Passwords must match');
    });

    it('rejects unchecked terms', async () => {
        await expect(registerSchema.validate({
            ...registerPayload,
            acceptTerms: false
        })).rejects.toThrow('Terms must be accepted');
    });
});

describe('loginSchema', () => {
    const loginPayload = {
        email: 'test@example.com',
        password: 'password123'
    };

    it('validates a correct payload', async () => {
        const result = await loginSchema.validate(loginPayload);
        expect(result).toBeTruthy();
    });

    it('rejects empty password', async () => {
        await expect(loginSchema.validate({
            ...loginPayload,
            password: ''
        })).rejects.toThrow('Password is required');
    });

    it('rejects empty email', async () => {
        await expect(loginSchema.validate({
            ...loginPayload,
            email: ''
        })).rejects.toThrow('Email is required');
    });
});
