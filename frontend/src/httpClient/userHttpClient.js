
import api from './api';

export const user ={

    login: async (email, password) => {
        const response = await api.post('/api/login', { email, password });
        localStorage.setItem('token', response.data.token);

        return response.data;
    },

    register: async (name, email, password) => {
        const response = await api.post('/api/register', { name, email, password });
        return response.data;
    },

    logout: async () => {
        localStorage.removeItem('token');
        return true;
    },

    isAuthenticated: () => {
        const token = localStorage.getItem('token');
        return null !== token;
    },

    getUser: async () => {
        const response = await api.get('/api/users/me');
        return response.data;
    },

    updateUser: async (name, email) => {
        const response = await api.put('/api/users/me', { name, email });
        return response.data;
    }
}