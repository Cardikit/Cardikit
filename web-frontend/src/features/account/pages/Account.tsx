import { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import Header from '@/assets/header.webp';
import { useAuth } from '@/contexts/AuthContext';
import api from '@/lib/axios';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
import Back from '@/components/Back';
import { useNavigate } from 'react-router-dom';

type FieldErrors = Record<string, string>;

const Account: React.FC = () => {
    const { user, loading: authLoading, refresh } = useAuth();
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [currentPassword, setCurrentPassword] = useState('');
    const [submitting, setSubmitting] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [success, setSuccess] = useState<string | null>(null);
    const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});
    const navigate = useNavigate();

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

    const onSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!user) return;

        setError(null);
        setSuccess(null);
        setFieldErrors({});

        if (!hasChanges) {
            setError('No changes to save');
            return;
        }

        if (!currentPassword.trim()) {
            setFieldErrors({ current_password: 'Current password is required to update your account.' });
            return;
        }

        if (password && password !== passwordConfirmation) {
            setFieldErrors({ password: 'Password confirmation does not match.' });
            return;
        }

        const payload: Record<string, unknown> = {
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
            await fetchCsrfToken();
            const response = await api.put('/@me', payload);
            setSuccess(response.data?.message ?? 'Account updated');
            setPassword('');
            setPasswordConfirmation('');
            setCurrentPassword('');
            await refresh();
            navigate('/dashboard');
        } catch (err: any) {
            const apiErrors = err?.response?.data?.errors;
            const apiMessage = err?.response?.data?.message || err?.response?.data?.error;

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
        } finally {
            setSubmitting(false);
        }
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
                                    disabled={authLoading}
                                />
                                <input
                                    type="password"
                                    value={passwordConfirmation}
                                    onChange={(e) => setPasswordConfirmation(e.target.value)}
                                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 mt-2"
                                    placeholder="Confirm new password"
                                    disabled={authLoading}
                                />
                                {fieldErrors.password && <p className="text-sm text-red-600">{fieldErrors.password}</p>}
                            </div>

                            <div className="space-y-2">
                                <label className="block text-sm font-medium text-gray-700">Current Password (required)</label>
                                <input
                                    type="password"
                                    value={currentPassword}
                                    onChange={(e) => setCurrentPassword(e.target.value)}
                                    className="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                    placeholder="Current password"
                                    disabled={authLoading}
                                    required
                                />
                                {fieldErrors.current_password && <p className="text-sm text-red-600">{fieldErrors.current_password}</p>}
                            </div>

                            {(error || success) && (
                                <div className={`text-sm ${success ? 'text-green-600' : 'text-red-600'}`}>
                                    {success || error}
                                </div>
                            )}

                            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-6">
                                <Link
                                    to="/dashboard"
                                    className="text-center w-full sm:w-auto border border-gray-300 text-gray-800 py-3 px-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    disabled={!hasChanges || submitting || authLoading}
                                    className={`w-full sm:w-auto bg-primary-500 text-gray-100 py-3 px-6 rounded-xl font-semibold shadow hover:bg-primary-900 transition-colors ${
                                        (!hasChanges || submitting || authLoading) ? 'opacity-60 cursor-not-allowed' : ''
                                    }`}
                                >
                                    {submitting ? 'Saving...' : 'Save changes'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    );
};

export default Account;
