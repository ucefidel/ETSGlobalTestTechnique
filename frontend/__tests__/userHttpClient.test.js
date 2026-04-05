import { user } from "../src/httpClient/userHttpClient";
import api from '../src/httpClient/api';

jest.mock('../src/httpClient/api');

describe('userHttpClient', () => {
    beforeEach(() => {
        localStorage.clear();
        jest.clearAllMocks();
    });

    it('isAuthenticated returns false when no token', () => {
        expect(user.isAuthenticated()).toBe(false);
    });

    it('isAuthenticated returns true when token exists', () => {
        localStorage.setItem('token', 'jwt-token');
        expect(user.isAuthenticated()).toBe(true);
    });

    it('login stores token and returns data', async () => {
        api.post.mockResolvedValue({ data: { token: 'jwt-token' } });

        const result = await user.login('test@example.com', 'password');

        expect(api.post).toHaveBeenCalledWith('/api/login', {
            email: 'test@example.com',
            password: 'password'
        });
        expect(localStorage.getItem('token')).toBe('jwt-token');
        expect(result).toEqual({ token: 'jwt-token' });
    });

    it('register calls register endpoint', async () => {
        const mockData = { message: 'User registered successfully' };
        api.post.mockResolvedValue({ data: mockData });

        const result = await user.register('Test User', 'test@example.com', 'password123');

        expect(api.post).toHaveBeenCalledWith('/api/register', {
            name: 'Test User',
            email: 'test@example.com',
            password: 'password123'
        });
        expect(result).toEqual(mockData);
    });

    it('logout removes token and returns true', async () => {
        localStorage.setItem('token', 'jwt-token');

        const result = await user.logout();

        expect(localStorage.getItem('token')).toBeNull();
        expect(result).toBe(true);
    });

    it('getUser fetches user profile', async () => {
        const mockUser = { id: '1', name: 'Test User', email: 'test@example.com' };
        api.get.mockResolvedValue({ data: mockUser });

        const result = await user.getUser();

        expect(api.get).toHaveBeenCalledWith('/api/users/me');
        expect(result).toEqual(mockUser);
    });

    it('updateUser calls update endpoint', async () => {
        const mockUser = { id: '1', name: 'New Name', email: 'new@example.com' };
        api.put.mockResolvedValue({ data: mockUser });

        const result = await user.updateUser('New Name', 'new@example.com');

        expect(api.put).toHaveBeenCalledWith('/api/users/me', {
            name: 'New Name',
            email: 'new@example.com'
        });
        expect(result).toEqual(mockUser);
    });
});
