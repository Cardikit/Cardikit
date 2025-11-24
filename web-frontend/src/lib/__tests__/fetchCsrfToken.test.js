import { describe, it, expect, vi, beforeEach } from 'vitest';
import { fetchCsrfToken } from '@/lib/fetchCsrfToken';
import api from '@/lib/axios';
// Mock axios instance
vi.mock('@/lib/axios', () => ({
    default: {
        get: vi.fn(),
        defaults: {
            headers: {
                common: {}
            }
        }
    }
}));
const mockedApi = api;
describe('fetchCsrfToken', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });
    it('sets X-CSRF-TOKEN header when successful', async () => {
        const mockToken = 'abc123';
        mockedApi.get.mockResolvedValueOnce({ data: { csrf_token: mockToken } });
        await fetchCsrfToken();
        expect(mockedApi.get).toHaveBeenCalledWith('/csrf-token');
        expect(mockedApi.defaults.headers.common['X-CSRF-TOKEN']).toBe(mockToken);
    });
});
