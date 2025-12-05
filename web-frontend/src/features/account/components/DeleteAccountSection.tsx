import { useDeleteAccount } from '../hooks/useDeleteAccount';

interface Props {
    onDeleted: () => Promise<void> | void;
}

/**
 * DeleteAccountSection
 * --------------------
 * Encapsulates the "danger zone" UI and flow for permanently deleting
 * the authenticated user's Cardikit account.
 *
 * Responsibilities:
 * - Renders a prominent "Delete account" action in the danger zone.
 * - Opens a confirmation modal that:
 *   - Explains the irreversible nature of account deletion.
 *   - Requires the user to enter their current password.
 *   - Shows field-level validation errors and a global error message.
 * - Integrates with the `useDeleteAccount` hook to:
 *   - Manage modal open/close state
 *   - Track password input and validation errors
 *   - Handle the delete request and loading state
 * - Calls the provided `onDeleted` callback after a successful deletion
 *   so the parent can clear auth state or redirect.
 *
 * UX details:
 * - The danger zone card is currently visible on large screens only.
 * - While `deleting` is true:
 *   - Buttons are disabled
 *   - The primary button label changes to "Deleting..."
 *
 * @component
 * @since 0.0.2
 */
const DeleteAccountSection: React.FC<Props> = ({ onDeleted }) => {
    const {
        deleteConfirmOpen,
        setDeleteConfirmOpen,
        deletePassword,
        setDeletePassword,
        fieldErrors,
        error,
        deleting,
        deleteAccount
    } = useDeleteAccount({ onDeleted });

    return (
        <>
            <div className="bg-white rounded-xl shadow p-4 space-y-3 border border-red-500">
                <h3 className="text-lg font-extrabold text-gray-900 font-inter text-red-500">Danger zone</h3>
                <p className="text-sm text-gray-600 font-inter">Permanently delete your account and all cards.</p>
                <button
                    onClick={() => setDeleteConfirmOpen(true)}
                    className="w-full bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg cursor-pointer hover:bg-red-600 transition-colors font-semibold"
                >
                    Delete account
                </button>
            </div>

            {deleteConfirmOpen && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
                    onClick={() => setDeleteConfirmOpen(false)}
                >
                    <div
                        className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <h3 className="text-lg font-semibold mb-2">Delete account</h3>
                        <p className="text-sm text-gray-600 mb-4">
                            This action is irreversible. Enter your password to confirm account deletion.
                        </p>
                        <div className="space-y-2">
                            <label className="block text-sm font-medium text-gray-700">Password</label>
                            <input
                                type="password"
                                value={deletePassword}
                                onChange={(e) => setDeletePassword(e.target.value)}
                                className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                placeholder="Enter your password"
                            />
                            {fieldErrors.delete_password && (
                                <p className="text-sm text-red-600">{fieldErrors.delete_password}</p>
                            )}
                        </div>

                        {error && <p className="text-sm text-red-600 mt-2">{error}</p>}

                        <div className="mt-4 flex items-center justify-end space-x-3">
                            <button
                                onClick={() => setDeleteConfirmOpen(false)}
                                className="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer"
                                disabled={deleting}
                            >
                                Cancel
                            </button>
                            <button
                                onClick={deleteAccount}
                                disabled={deleting}
                                className="px-4 py-2 rounded bg-red-500 text-white font-semibold hover:bg-red-600 disabled:opacity-60 cursor-pointer"
                            >
                                {deleting ? 'Deleting...' : 'Delete account'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default DeleteAccountSection;
