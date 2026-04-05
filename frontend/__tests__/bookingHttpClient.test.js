import { bookingHttpClient } from '../src/httpClient/bookingHttpClient';
import api from '../src/httpClient/api';

jest.mock('../src/httpClient/api');

describe('bookingHttpClient', () => {

    beforeEach(() => {
        jest.clearAllMocks();
    });

    it('getAllBooking returns bookings', async () => {
        const mockData = [{
            id: '1', session: { language: 'Anglais' }, bookedAt: '2026-04-01'
        }];

        api.get.mockResolvedValue({ data: mockData });

        const result = await bookingHttpClient.getAllBooking();

        expect(api.get).toHaveBeenCalledWith('/api/bookings/me');
        expect(result).toEqual(mockData);
    });

    it('Book calls endpoint with sessionId', async () => {
        api.post.mockResolvedValue({ data: { id: '1' } });

        await bookingHttpClient.book('session-test-id');

        expect(api.post).toHaveBeenCalledWith('/api/bookings/book',
            { sessionId: 'session-test-id' });
    });

    it('cancel calls delete endpoint with booking id', async () => {
        api.delete.mockResolvedValue({ data: null });

        const result = await bookingHttpClient.cancel('booking-123');

        expect(api.delete).toHaveBeenCalledWith('/api/bookings/cancel/booking-123');
        expect(result).toBeNull();
    });
});
