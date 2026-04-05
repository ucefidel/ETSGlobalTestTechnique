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

    it('getSession returns a single session by id', async () => {
        const mockSession = { id: '1', language: 'Anglais', availableSeats: 10 };
        api.get.mockResolvedValue({ data: mockSession });

        const result = await sessionHttpClient.getSession('1');

        expect(api.get).toHaveBeenCalledWith('/api/sessions/1');
        expect(result).toEqual(mockSession);
    });

    it('createSession posts session data', async () => {
        const sessionData = { language: 'Anglais', dateAt: '2026-05-01', hourAt: '09:00', location: 'Paris', availableSeats: 10 };
        api.post.mockResolvedValue({ data: { id: '1', ...sessionData } });

        const result = await sessionHttpClient.createSession(sessionData);

        expect(api.post).toHaveBeenCalledWith('/api/sessions', sessionData);
        expect(result).toEqual({ id: '1', ...sessionData });
    });

    it('updateSession puts session data', async () => {
        const sessionData = { language: 'Français', dateAt: '2026-05-05', hourAt: '14:00', location: 'Lyon', availableSeats: 5 };
        api.put.mockResolvedValue({ data: { id: '1', ...sessionData } });

        const result = await sessionHttpClient.updateSession('1', sessionData);

        expect(api.put).toHaveBeenCalledWith('/api/sessions/1', sessionData);
        expect(result).toEqual({ id: '1', ...sessionData });
    });

    it('deleteSession deletes session by id', async () => {
        api.delete.mockResolvedValue({ data: null });

        const result = await sessionHttpClient.deleteSession('1');

        expect(api.delete).toHaveBeenCalledWith('/api/sessions/1');
        expect(result).toBeNull();
    });

})