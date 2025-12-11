import { httpClient } from '@/services/httpClient';
import type { AnalyticsSummaryResponse } from '@/types/analytics';

export const fetchAnalyticsSummary = async (options?: { days?: number; cardId?: number | null }): Promise<AnalyticsSummaryResponse> => {
    const hasDays = typeof options?.days === 'number';
    const safeDays = hasDays ? Math.max(1, Math.min(options?.days ?? 30, 365)) : null;
    const params: string[] = [];
    if (hasDays && safeDays !== null) params.push(`days=${safeDays}`);
    if (typeof options?.cardId === 'number') params.push(`card_id=${options.cardId}`);
    const query = params.length ? `?${params.join('&')}` : '';
    return httpClient.get<AnalyticsSummaryResponse>(`/analytics/summary${query}`);
};
