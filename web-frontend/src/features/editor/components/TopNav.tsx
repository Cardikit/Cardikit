import { useState } from 'react';
import { MdModeEdit } from 'react-icons/md';
import { Link, useParams } from 'react-router-dom';
import type { CardType } from '@/types/card';
import { useCreateCard } from '@/features/editor/hooks/useCreateCard';
import { useUpdateCard } from '@/features/editor/hooks/useUpdateCard';
import { useNavigate } from 'react-router-dom';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
import axios from 'axios';
import * as yup from 'yup';
import { cardSchema } from '@/features/editor/validationSchema';

interface TopNavProps {
    card: CardType;
    setOpen: (open: boolean) => void;
    formError: string | null;
    setFormError: (error: string | null) => void;
    setItemErrors: (errors: Record<string, string>) => void;
}

const TopNav: React.FC<TopNavProps> = ({ card, setOpen, formError, setFormError, setItemErrors }) => {
    const { createCard, loading: creating, error: createError } = useCreateCard();
    const { updateCard, loading: updating, error: updateError } = useUpdateCard();
    const [localError, setLocalError] = useState<string | null>(null);
    const navigate = useNavigate();
    const { id } = useParams();
    const isSaving = creating || updating;
    const defaultColor = '#1D4ED8';

    const onSubmit = async () => {
        const payload = {
            name: (card.name ?? '').trim(),
            color: (card.color ?? defaultColor).trim(),
            banner_image: card.banner_image ?? null,
            avatar_image: card.avatar_image ?? null,
            card_items: (card.items ?? []).map(item => ({
                ...item,
                value: (item.value ?? '').trim(),
            }))
        };

        try {
            setLocalError(null);
            setFormError(null);
            setItemErrors({});

            await cardSchema.validate(payload, { abortEarly: false });
            await fetchCsrfToken();

            if (id) {
                await updateCard(payload, Number(id));
            } else {
                await createCard(payload);
            }
            navigate('/dashboard');
        } catch (error) {
            if (error instanceof yup.ValidationError) {
                const itemErrorMap: Record<string, string> = {};

                error.inner.forEach(err => {
                    if (err.path === 'name') {
                        setFormError(err.message);
                        return;
                    }

                    const match = err.path?.match(/^card_items\[(\d+)\]/);
                    if (match) {
                        const index = Number(match[1]);
                        const target = payload.card_items[index];
                        const key = target?.id ?? target?.client_id ?? String(index);
                        if (!itemErrorMap[key]) {
                            itemErrorMap[key] = err.message;
                        }
                    }
                });

                if (Object.keys(itemErrorMap).length > 0) {
                    setItemErrors(itemErrorMap);
                }

                const topLevelError = error.inner.find(err => !err.path || err.path === 'name') ?? error.inner[0];
                if (topLevelError) {
                    setLocalError(topLevelError.errors[0]);
                }
                return;
            }

            if (axios.isAxiosError(error)) {
                const apiError = error.response?.data?.errors?.name?.[0]
                    ?? error.response?.data?.message
                    ?? error.response?.data?.error;
                setLocalError(apiError || 'Something went wrong. Please try again.');

                const itemErrorsFromApi = error.response?.data?.errors;
                if (Array.isArray(itemErrorsFromApi)) {
                    const itemErrorMap: Record<string, string> = {};
                    itemErrorsFromApi.forEach((errItem: any, idx: number) => {
                        if (!errItem) return;
                        const target = payload.card_items[idx];
                        const key = target?.id ?? target?.client_id ?? String(idx);

                        if (errItem.errors && typeof errItem.errors === 'object') {
                            const firstFieldError = Object.values(errItem.errors)[0] as string[] | undefined;
                            const message = Array.isArray(firstFieldError) ? firstFieldError[0] : null;
                            if (message) {
                                itemErrorMap[key] = message;
                            }
                        } else if (errItem.type) {
                            itemErrorMap[key] = errItem.type;
                        }
                    });

                    if (Object.keys(itemErrorMap).length > 0) {
                        setItemErrors(itemErrorMap);
                    }
                }
                return;
            }

            setLocalError('Something went wrong. Please try again.');
        }
    }

    return (
        <div className="fixed top-0 w-full z-10 p-4 text-gray-800 bg-gray-300">
            <div className="flex items-center justify-between">
                <Link to="/dashboard" className="font-inter cursor-pointer">Cancel</Link>
                <div onClick={() => setOpen(true)} className="flex items-center space-x-2 cursor-pointer">
                    <h1 className="text-xl font-semibold font-inter">{card.name}</h1>
                    <MdModeEdit className="text-2xl" />
                </div>
                <p
                    onClick={!isSaving ? onSubmit : undefined}
                    className={`font-inter ${isSaving ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer'}`}
                >
                    {isSaving ? 'Saving...' : 'Save'}
                </p>
            </div>
            {(localError || formError || (id ? updateError : createError)) && (
                <p className="text-red-600 text-sm text-center mt-2 font-inter">
                    {localError || formError || (id ? updateError : createError)}
                </p>
            )}
        </div>
    );
}

export default TopNav;
