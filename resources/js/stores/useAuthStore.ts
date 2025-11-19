import { ref, computed, type Ref, type ComputedRef } from 'vue';
import apiClient from '@/api/client';
import type {
    User,
    AuthResponse,
    RegisterCredentials,
    LoginCredentials
} from '@/types/api';

interface AuthStore {
    user: Ref<User | null>;
    token: Ref<string | null>;
    isAuthenticated: ComputedRef<boolean>;
    register: (credentials: RegisterCredentials) => Promise<AuthResponse>;
    login: (credentials: LoginCredentials) => Promise<AuthResponse>;
    logout: () => Promise<void>;
    getCurrentUser: () => Promise<User>;
    updateBalance: (newBalance: string | number) => void;
}

const user = ref<User | null>(null);
const token = ref<string | null>(localStorage.getItem('auth_token'));

export const useAuthStore = (): AuthStore => {
    const isAuthenticated = computed(() => !!token.value);

    const register = async (credentials: RegisterCredentials): Promise<AuthResponse> => {
        const response = await apiClient.post<AuthResponse>('/register', credentials);
        token.value = response.data.token;
        user.value = response.data.user;
        localStorage.setItem('auth_token', token.value);
        return response.data;
    };

    const login = async (credentials: LoginCredentials): Promise<AuthResponse> => {
        const response = await apiClient.post<AuthResponse>('/login', credentials);
        token.value = response.data.token;
        user.value = response.data.user;
        localStorage.setItem('auth_token', token.value);
        return response.data;
    };

    const logout = async (): Promise<void> => {
        try {
            await apiClient.post('/logout');
        } finally {
            token.value = null;
            user.value = null;
            localStorage.removeItem('auth_token');
        }
    };

    const getCurrentUser = async (): Promise<User> => {
        const response = await apiClient.get<User>('/me');
        user.value = response.data;
        return response.data;
    };

    const updateBalance = (newBalance: string | number): void => {
        if (user.value) {
            user.value.balance = newBalance;
        }
    };

    return {
        user,
        token,
        isAuthenticated,
        register,
        login,
        logout,
        getCurrentUser,
        updateBalance,
    };
};
