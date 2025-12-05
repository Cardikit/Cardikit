import { useEffect, useMemo, useState } from 'react';
import type { User } from '@/types/user';
import { userService, type UpdateAccountPayload } from '@/services/userService';
import { ApiError } from '@/services/httpClient';
import { extractErrorMessage } from '@/services/errorHandling';

export type FieldErrors = Record<string, string>;

interface Options {
    onUpdated?: () => Promise<void> | void;
}

/**
 * useAccountForm
 * ---------------
 * Stateful hook that powers the Cardikit account settings form.
 *
 * Responsibilities:
 * - Initialize form state from the authenticated `user`:
 *   - Pre-fills name and email when the user object changes.
 * - Track controlled inputs:
 *   - `name`, `email`, `password`, `passwordConfirmation`, `currentPassword`.
 * - Compute `hasChanges` to determine if:
 *   - Name has changed (trimmed comparison),
 *   - Email has changed (case-insensitive comparison),
 *   - Or a new password has been entered.
 * - Perform client-side validation before submit:
 *   - Prevent submission when no changes exist.
 *   - Require `currentPassword` for any update.
 *   - Ensure new password matches confirmation when provided.
 * - Build a minimal `UpdateAccountPayload`:
 *   - Only includes fields that actually changed from the current user.
 * - Call `userService.updateAccount` and:
 *   - Reset sensitive fields on success (passwords, current password).
 *   - Invoke the optional `onUpdated` callback to let the caller refresh
 *     auth state or perform navigation.
 *   - Map API validation errors into `fieldErrors`.
 *   - Surface a human-readable error message via `error` using `ApiError`
 *     metadata or `extractErrorMessage`.
 *
 * Returned values:
 * - Form state: `name`, `email`, `password`, `passwordConfirmation`, `currentPassword`.
 * - Setters: `setName`, `setEmail`, `setPassword`, `setPasswordConfirmation`, `setCurrentPassword`.
 * - Status flags: `submitting`, `hasChanges`.
 * - Messaging: `error`, `success`, `fieldErrors`.
 * - Handlers: `onSubmit` (submit handler for the `<form>` element).
 *
 * @param user    Current authenticated user, or null if not loaded yet.
 * @param options Optional configuration, including `onUpdated` callback.
 * @since 0.0.2
 */
export const useAccountForm = (user: User | null, options?: Options) => {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [currentPassword, setCurrentPassword] = useState('');
    const [submitting, setSubmitting] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [success, setSuccess] = useState<string | null>(null);
    const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});

    useEffect(() => {
        if (user) {
            setName(user.name ?? '');
            setEmail(user.email ?? '');
        }
    }, [user]);

    const hasChanges = useMemo(() => {
        if (!user) return false;
        const trimmedName = name.trim();
        const trimmedEmail = email.trim();
        return (
            trimmedName !== (user.name ?? '').trim() ||
            trimmedEmail.toLowerCase() !== (user.email ?? '').toLowerCase() ||
            password.trim() !== ''
        );
    }, [user, name, email, password]);

    const validate = (): boolean => {
        setError(null);
        setSuccess(null);
        setFieldErrors({});

        if (!hasChanges) {
            setError('No changes to save');
            return false;
        }

        if (!currentPassword.trim()) {
            setFieldErrors({ current_password: 'Current password is required to update your account.' });
            return false;
        }

        if (password && password !== passwordConfirmation) {
            setFieldErrors({ password: 'Password confirmation does not match.' });
            return false;
        }

        return true;
    };

    const onSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!user) return;

        if (!validate()) return;

        const payload: UpdateAccountPayload = {
            current_password: currentPassword,
        };

        const trimmedName = name.trim();
        const trimmedEmail = email.trim();

        if (trimmedName && trimmedName !== user.name) {
            payload.name = trimmedName;
        }
        if (trimmedEmail && trimmedEmail.toLowerCase() !== (user.email ?? '').toLowerCase()) {
            payload.email = trimmedEmail;
        }
        if (password.trim()) {
            payload.password = password;
            payload.password_confirmation = passwordConfirmation;
        }

        setSubmitting(true);
        try {
            const response = await userService.updateAccount(payload);
            setSuccess(response?.message ?? 'Account updated');
            setPassword('');
            setPasswordConfirmation('');
            setCurrentPassword('');
            await options?.onUpdated?.();
        } catch (err: any) {
            if (err instanceof ApiError && err.data && typeof err.data === 'object') {
                const apiErrors = (err.data as any).errors;
                const apiMessage = (err.data as any).message || (err.data as any).error;

                if (apiErrors && typeof apiErrors === 'object') {
                    const errors: FieldErrors = {};
                    Object.entries(apiErrors).forEach(([field, msgs]) => {
                        if (Array.isArray(msgs) && msgs.length > 0) {
                            errors[field] = String(msgs[0]);
                        }
                    });
                    setFieldErrors(errors);
                }

                setError(apiMessage || 'Failed to update account');
            } else {
                setError(extractErrorMessage(err, 'Failed to update account'));
            }
        } finally {
            setSubmitting(false);
        }
    };

    return {
        name,
        email,
        password,
        passwordConfirmation,
        currentPassword,
        setName,
        setEmail,
        setPassword,
        setPasswordConfirmation,
        setCurrentPassword,
        submitting,
        error,
        success,
        fieldErrors,
        onSubmit,
        hasChanges,
    };
};
