import { useState } from 'react';
import type { CardType } from '@/types/card';
import * as yup from 'yup';
import { cardSchema } from '@/features/editor/validationSchema';
import { useCreateCard } from './useCreateCard';
import { useUpdateCard } from './useUpdateCard';
import { ApiError } from '@/services/httpClient';

interface SaveArgs {
    card: CardType;
    cardId?: number;
    setFormError: (error: string | null) => void;
    setItemErrors: (errors: Record<string, string>) => void;
    onSuccess?: () => void;
}

/**
 * useSaveCard
 * -----------
 * Centralizes card save logic (create + update) for the editor.
 *
 * - Builds payload from a CardType (normalizes defaults).
 * - Runs schema validation and maps field/item errors back to UI callbacks.
 * - Invokes create or update via the existing hooks, exposing unified saving/error state.
 *
 * @since 0.0.2
 */
export const useSaveCard = () => {
    const { createCard, loading: creating, error: createError } = useCreateCard();
    const { updateCard, loading: updating, error: updateError } = useUpdateCard();
    const [localError, setLocalError] = useState<string | null>(null);
    const saving = creating || updating;

    const save = async ({ card, cardId, setFormError, setItemErrors, onSuccess }: SaveArgs) => {
        const defaultColor = '#1D4ED8';
        const items = (card.items ?? (card as any).card_items ?? []).map((item: any) => ({
            ...item,
            value: (item?.value ?? '').trim(),
        }));

        const payload = {
            name: (card.name ?? '').trim(),
            color: (card.color ?? defaultColor).trim(),
            theme: card.theme ?? 'default',
            banner_image: (card as any).banner_image ?? null,
            avatar_image: (card as any).avatar_image ?? null,
            card_items: items,
        };

        setLocalError(null);
        setFormError(null);
        setItemErrors({});

        try {
            await cardSchema.validate(payload, { abortEarly: false });

            if (cardId) {
                await updateCard(payload, cardId);
            } else {
                await createCard(payload);
            }

            onSuccess?.();
        } catch (error: any) {
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
                        const key = (target as any)?.id ?? (target as any)?.client_id ?? String(index);
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

            if (error instanceof ApiError) {
                const data: any = error.data;
                const apiError = data?.errors?.name?.[0] ?? data?.message ?? data?.error;
                setLocalError(apiError || 'Something went wrong. Please try again.');

                const itemErrorsFromApi = data?.errors;
                if (Array.isArray(itemErrorsFromApi)) {
                    const itemErrorMap: Record<string, string> = {};
                    itemErrorsFromApi.forEach((errItem: any, idx: number) => {
                        if (!errItem) return;
                        const target = payload.card_items[idx];
                        const key = (target as any)?.id ?? (target as any)?.client_id ?? String(idx);

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
            throw error;
        }
    };

    return {
        save,
        saving,
        error: localError || createError || updateError,
    };
};
