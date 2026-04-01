import {sessionHttpClient} from "../src/httpClient/sessionHttpClient";
import api from '../src/httpClient/api';


jest.mock('../src/httpClient/api');

describe('sessionHttpClient', () => {

    beforeEach(() => {
        jest.clearAllMocks();
    })

    it('getSessions returns paginated data', async () => {
        const mockData = {data: [{ id: '1', language: 'Anglais', availableSeats: 10 }],
            total: 1, page: 1, pages: 1
        };

        api.get.mockResolvedValue({ data: mockData });

        const result = await sessionHttpClient.getSessions(1, 10, false);

        expect(api.get).toHaveBeenCalledWith('/api/sessions', {
            params: { page: 1, limit: 10, availableOnly: false }
        });
        expect(result).toEqual(mockData);
    });

    it('getSessions with availableSeats', async () => {
        api.get.mockResolvedValue({ data: { data: [], total: 0, page: 1, pages: 0 } });

        await sessionHttpClient.getSessions(1, 10, true);

        expect(api.get).toHaveBeenCalledWith('/api/sessions', {
            params: { page: 1,
                limit: 10, availableOnly: true }
        });
    });

})