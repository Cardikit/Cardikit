import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import Header from '@/assets/header.webp';
import { useAuth } from '@/contexts/AuthContext';
import Back from '@/components/Back';
import AccountDetailsForm from '../components/AccountDetailsForm';
import DeleteAccountSection from '../components/DeleteAccountSection';
import { billingService } from '@/services/billingService';
import { toApiError } from '@/services/httpClient';

/**
 * Account Screen
 * --------------
 * This screen allows authenticated users to manage their Cardikit account.
 *
 * Features:
 * - Update profile information (name, email, password)
 * - Requires current password for security-sensitive changes
 * - Automatically refreshes the authenticated session after updates
 * - Provides navigation back to the dashboard
 * - Includes a dedicated section for permanently deleting the account
 *
 * The UI is optimized for mobile, using clean spacing, modern typography,
 * and consistent Cardikit styling throughout.
 *
 * @since 0.0.2
 */
const Account: React.FC = () => {
    const { user, loading: authLoading, refresh } = useAuth();
    const navigate = useNavigate();
    const [billingLoading, setBillingLoading] = useState(false);
    const [billingError, setBillingError] = useState<string | null>(null);

    const handleUpdated = async () => {
        await refresh();
        navigate('/');
    };

    const openBillingPortal = async () => {
        setBillingLoading(true);
        setBillingError(null);
        try {
            const { url } = await billingService.portal();
            window.location.href = url;
        } catch (err) {
            const apiErr = toApiError(err);
            setBillingError(apiErr.message || 'Unable to open billing portal.');
        } finally {
            setBillingLoading(false);
        }
    };

    const handleDeleted = async () => {
        await refresh();
        navigate('/login');
    };

    return (
        <main className="min-h-dvh bg-[#E3E3E3] flex flex-col">
            <Back />
            <div className="flex flex-col items-center px-6 pt-10 pb-12 flex-1">
                <img src={Header} alt="Cardikit" className="w-3/4 max-w-sm mx-auto" />

                <div className="w-full bg-[#FBFBFB] rounded-4xl flex flex-col items-center flex-1 px-6 py-10 mt-8 shadow-lg">
                    <div className="w-full max-w-xl space-y-6">
                        <div className="text-center space-y-3">
                            <p className="text-sm uppercase tracking-[0.2em] text-gray-400 font-semibold">Account</p>
                            <h1 className="text-2xl md:text-3xl font-bold text-gray-900 font-inter">Manage your profile</h1>
                            <p className="text-gray-600 font-inter">
                                Update your name, email, or change your password. For security, please confirm your current password.
                            </p>
                        </div>

                        <AccountDetailsForm user={user} authLoading={authLoading} onUpdated={handleUpdated} />

                        <div className="space-y-3">
                            <button
                                onClick={openBillingPortal}
                                disabled={billingLoading}
                                className="w-full bg-primary-500 hover:bg-primary-600 disabled:bg-primary-300 disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-xl transition-colors"
                            >
                                {billingLoading ? 'Opening billing...' : 'Manage billing'}
                            </button>
                            {billingError && <p className="text-sm text-red-600 text-center">{billingError}</p>}
                        </div>

                        <Link
                            to="/"
                            className="text-center w-full sm:w-auto border border-gray-300 text-gray-800 py-3 px-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors block sm:inline-block"
                        >
                            Back to dashboard
                        </Link>
                    </div>
                </div>

                <div className="w-full bg-[#FBFBFB] rounded-4xl flex flex-col items-center flex-1 px-6 py-10 mt-8 shadow-lg">
                    <div className="w-full max-w-xl">
                        <DeleteAccountSection onDeleted={handleDeleted} />
                    </div>
                </div>
            </div>
        </main>
    );
};

export default Account;
