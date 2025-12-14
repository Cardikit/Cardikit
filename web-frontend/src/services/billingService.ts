import { httpClient } from './httpClient';

export const billingService = {
    checkout: (plan: 'monthly' | 'annual') =>
        httpClient.post<{ url: string }>('/billing/checkout', { plan }),
    portal: () => httpClient.post<{ url: string }>('/billing/portal'),
};
