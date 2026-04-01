import {  user } from "../src/httpClient/userHttpClient";

describe('userHttpClient', () => {
    beforeEach(() => {
        localStorage.clear();
    });

    it('isAuthenticated returns false when no token', () => {
        expect(user.isAuthenticated()).toBe(false);
    })
});