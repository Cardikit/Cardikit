import { useAccountForm } from '../hooks/useAccountForm';
import type { User } from '@/types/user';

interface Props {
    user: User | null;
    authLoading: boolean;
    onUpdated: () => Promise<void> | void;
}

/**
 * AccountDetailsForm
 * ------------------
 * Form for updating the authenticated user's account details in Cardikit.
 *
 * Responsibilities:
 * - Displays controlled inputs for:
 *   - Name
 *   - Email address
 *   - New password and confirmation
 *   - Current password (required to apply any changes)
 * - Integrates with the `useAccountForm` hook to manage:
 *   - Local form state
 *   - Client-side and server-side validation errors
 *   - Submission lifecycle (submitting, success, error)
 *   - Dirty-state tracking to enable/disable the submit button
 * - Calls the provided `onUpdated` callback once the account
 *   has been successfully updated, allowing the parent to refresh
 *   auth state or perform navigation.
 *
 * UX details:
 * - Disables fields while `authLoading` is true.
 * - Shows field-level validation messages and a global error/success message.
 * - Disables the "Save changes" button when there are no pending changes
 *   or while a submission is in progress.
 *
 * @component
 * @since 0.0.2
 */
const AccountDetailsForm: React.FC<Props> = ({ user, authLoading, onUpdated }) => {
    const {
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
    } = useAccountForm(user, { onUpdated });

    return (
        <form onSubmit={onSubmit} className="space-y-5">
            <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Your name"
                    disabled={authLoading}
                />
                {fieldErrors.name && <p className="text-sm text-red-600">{fieldErrors.name}</p>}
            </div>

            <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="you@example.com"
                    disabled={authLoading}
                />
                {fieldErrors.email && <p className="text-sm text-red-600">{fieldErrors.email}</p>}
            </div>

            <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">New Password</label>
                <input
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Leave blank to keep current password"
                />
                {fieldErrors.password && <p className="text-sm text-red-600">{fieldErrors.password}</p>}
            </div>

            <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input
                    type="password"
                    value={passwordConfirmation}
                    onChange={(e) => setPasswordConfirmation(e.target.value)}
                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Retype new password"
                />
            </div>

            <div className="space-y-2">
                <label className="block text-sm font-medium text-gray-700">Current Password</label>
                <input
                    type="password"
                    value={currentPassword}
                    onChange={(e) => setCurrentPassword(e.target.value)}
                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Required for any changes"
                />
                {fieldErrors.current_password && <p className="text-sm text-red-600">{fieldErrors.current_password}</p>}
            </div>

            {error && <p className="text-sm text-red-600">{error}</p>}
            {success && <p className="text-sm text-green-600">{success}</p>}

            <button
                type="submit"
                disabled={submitting || !hasChanges}
                className="w-full bg-primary-500 text-white px-4 py-3 rounded-xl shadow-lg cursor-pointer hover:bg-primary-900 disabled:opacity-60 transition-colors font-semibold"
            >
                {submitting ? 'Saving...' : 'Save changes'}
            </button>
        </form>
    );
};

export default AccountDetailsForm;
