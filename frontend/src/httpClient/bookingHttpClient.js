import api from "./api";

export const bookingHttpClient = {

    getAllBooking: async () => {
        const response = await api.get('/api/bookings/me');
        return response.data;
    },

    book: async (sessionId) => {
        const response = await api.post('/api/bookings/book', { sessionId });
        return response.data;
    },

    cancel: async (id) => {
        const response = await api.delete(`/api/bookings/cancel/${id}`);
        return response.data;
    },
}
