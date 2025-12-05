import { httpClient } from './httpClient';
import { cardSchema } from '@/features/editor/validationSchema';
import type { CardType, ItemType } from '@/types/card';
import type { ThemeMeta } from '@/types/theme';

export const normalizeCard = (card: CardType): CardType => ({
    ...card,
    color: card.color ?? '#1D4ED8',
    theme: card.theme ?? 'default',
    banner_image: card.banner_image ?? null,
    avatar_image: card.avatar_image ?? null,
    items: card.items ?? [],
});

export interface CardPayload {
    name: string;
    color: string;
    theme?: string;
    banner_image?: string | null;
    avatar_image?: string | null;
    card_items: ItemType[];
}

export const cardService = {
    list: async (): Promise<CardType[]> => {
        const cards = await httpClient.get<CardType[]>('/@me/cards');
        return cards.map(normalizeCard);
    },

    get: async (id: number): Promise<CardType> => {
        const card = await httpClient.get<CardType>(`/@me/cards/${id}`);
        return normalizeCard(card);
    },

    create: async (payload: CardPayload): Promise<CardType> => {
        await cardSchema.validate(payload, { abortEarly: false });
        const card = await httpClient.post<CardType>('/@me/cards', payload);
        return normalizeCard(card);
    },

    update: async (id: number, payload: CardPayload): Promise<CardType> => {
        await cardSchema.validate(payload, { abortEarly: false });
        const card = await httpClient.put<CardType>(`/@me/cards/${id}`, payload);
        return normalizeCard(card);
    },

    delete: (id: number) => httpClient.delete<{ message: string }>(`/@me/cards/${id}`),

    regenerateQr: (id: number, logo?: string | null) =>
        httpClient.post<{ message: string; qr_image_url?: string }>(`/@me/cards/${id}/qr`, logo ? { logo } : {}),

    themes: () => httpClient.get<ThemeMeta[]>('/themes'),
};
