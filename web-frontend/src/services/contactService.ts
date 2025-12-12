import api from '@/lib/axios';
import { httpClient } from './httpClient';
import type { ContactPage } from '@/types/contact';

export const contactService = {
    list: async (page: number = 1, cardId?: number | null) => {
        const params = new URLSearchParams();
        params.set('page', String(page));
        if (cardId) params.set('card_id', String(cardId));
        return httpClient.get<ContactPage>(`/contacts?${params.toString()}`);
    },

    exportCsv: async (cardId?: number | null) => {
        const params = new URLSearchParams();
        if (cardId) params.set('card_id', String(cardId));
        const response = await api.get(`/contacts/export?${params.toString()}`, { responseType: 'blob' });
        return response.data as Blob;
    },
};
