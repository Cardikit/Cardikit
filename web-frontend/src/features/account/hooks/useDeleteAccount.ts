import { useState } from 'react';
import { userService } from '@/services/userService';
import { ApiError } from '@/services/httpClient';
import { extractErrorMessage } from '@/services/errorHandling';
import type { FieldErrors } from './useAccountForm';

interface Options {
    onDeleted?: () => Promise<void> | void;
}

/**
 * useDeleteAccount
 * -----------------
 * Hook that encapsulates the state and logic for deleting
 * the authenticated user's Cardikit account.
 *
 * Responsibilities:
 * - Manage confirmation modal state:
 *   - `deleteConfirmOpen` / `setDeleteConfirmOpen` to show/hide the dialog.
 * - Track password input used to authorize deletion:
 *   - `deletePassword` / `setDeletePassword`.
 * - Perform basic client-side validation:
 *   - Require a non-empty password before attempting deletion.
 * - Call `userService.deleteAccount` with the provided password.
 * - Normalize and expose API validation errors and messages:
 *   - Maps backend `errors` into `fieldErrors` (with `password` remapped
 *     to `delete_password` for UI consistency).
 *   - Exposes a user-friendly `error` string for non-field failures.
 * - Drive UI loading state via `deleting`.
 * - On success:
 *   - Closes the confirmation modal.
 *   - Clears the password field.
 *   - Invokes the optional `onDeleted` callback, allowing the caller
 *     to clear auth state, redirect, or perform any cleanup.
 *
 * Returned values:
 * - Modal state: `deleteConfirmOpen`, `setDeleteConfirmOpen`.
 * - Input state: `deletePassword`, `setDeletePassword`.
 * - Validation / error state: `fieldErrors`, `error`.
 * - Status flag: `deleting`.
 * - Action: `deleteAccount` (to be bound to the "Delete" button).
 *
 * @param options Optional configuration, including `onDeleted` callback.
 * @since 0.0.2
 */
export const useDeleteAccount = (options?: Options) => {
    const [deleteConfirmOpen, setDeleteConfirmOpen] = useState(false);
    const [deletePassword, setDeletePassword] = useState('');
    const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});
    const [error, setError] = useState<string | null>(null);
    const [deleting, setDeleting] = useState(false);

    const deleteAccount = async () => {
        setError(null);
        setFieldErrors({});

        if (!deletePassword.trim()) {
            setFieldErrors({ delete_password: 'Password is required to delete your account.' });
            return;
        }

        setDeleting(true);
        try {
            await userService.deleteAccount(deletePassword);
            setDeleteConfirmOpen(false);
            setDeletePassword('');
            await options?.onDeleted?.();
        } catch (err: any) {
            if (err instanceof ApiError && err.data && typeof err.data === 'object') {
                const apiErrors = (err.data as any).errors;
                const apiMessage = (err.data as any).message || (err.data as any).error;

                if (apiErrors && typeof apiErrors === 'object') {
                    const errors: FieldErrors = {};
                    Object.entries(apiErrors).forEach(([field, msgs]) => {
                        if (Array.isArray(msgs) && msgs.length > 0) {
                            errors[field === 'password' ? 'delete_password' : field] = String(msgs[0]);
                        }
                    });
                    setFieldErrors(errors);
                }
                setError(apiMessage || 'Failed to delete account');
            } else {
                setError(extractErrorMessage(err, 'Failed to delete account'));
            }
        } finally {
            setDeleting(false);
        }
    };

    return {
        deleteConfirmOpen,
        setDeleteConfirmOpen,
        deletePassword,
        setDeletePassword,
        fieldErrors,
        error,
        deleting,
        deleteAccount,
    };
};
