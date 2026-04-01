import api from './api';

export const sessionHttpClient = {
    getSessions: async (page = 1, limit = 10, availableOnly = false) => {
        const response = await api.get('/api/sessions', {
            params: {
                page, limit,
                availableOnly
            }
        });
        return response.data;
    },

    getSession: async (id) => {
        const response = await api.get(`/api/sessions/${id}`);
        return response.data;
    },

    createSession: async (data) => {
        const response = await api.post('/api/sessions',  data);
        return response.data;
    },
    updateSession: async (id, data) => {
        const response = await api.put(`/api/sessions/${id}`, data);
        return response.data;
    },
    deleteSession: async (id) => {
        const response = await api.delete(`/api/sessions/${id}`);
        return response.data;
    }

}
