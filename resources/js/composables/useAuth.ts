import { ref } from 'vue';

export const useAuth = () => {
    const token = ref<string | null>(localStorage.getItem('sanctum_token'));

    const storeToken = (newToken: string): void => {
        token.value = newToken;
        localStorage.setItem('sanctum_token', newToken);
    };

    const getToken = (): string | null => {
        return localStorage.getItem('sanctum_token');
    };

    const clearToken = (): void => {
        token.value = null;
        localStorage.removeItem('sanctum_token');
    };

    const initializeToken = (): void => {
        const storedToken = localStorage.getItem('sanctum_token');
        if (storedToken) {
            token.value = storedToken;
        }
    };

    return {
        token,
        storeToken,
        getToken,
        clearToken,
        initializeToken,
    };
};
