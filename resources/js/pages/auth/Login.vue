<template>
    <div class="min-h-screen flex flex-col items-center justify-center   p-4">
        <!-- Main Login Card -->
        <div class="  p-8 rounded-lg shadow-2xl w-full max-w-md border border-gray-700 mb-8">
            <h2 class="text-3xl font-bold   mb-2">Login</h2>
            <p class="text-gray-400 text-sm mb-6">Mini Wallet Application</p>

            <div v-if="error" class="mb-4 p-4 bg-red-900/30 border border-red-700 rounded text-red-200 text-sm">
                {{ error }}
            </div>

            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label class="block   mb-2 text-sm font-medium">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full px-4 py-2   border border-gray-600 rounded   placeholder-gray-400 focus:outline-none focus:border-blue-500 transition"
                        placeholder="your@email.com"
                    />
                </div>

                <div>
                    <label class="block   mb-2 text-sm font-medium">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="w-full px-4 py-2   border border-gray-600 rounded   placeholder-gray-400 focus:outline-none focus:border-blue-500 transition"
                        placeholder="password"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded transition"
                >
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>


        </div>

        <!-- Demo Users Table -->
        <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full max-w-4xl border border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-2">Demo Users</h3>
            <p class="text-gray-400 text-sm mb-6">
                <span v-if="loading" class="text-blue-400">Loading demo users...</span>
                <span v-else>Click on any row to auto-fill the login form. Password: <span class="text-yellow-400 font-mono">password123</span> for all accounts</span>
            </p>

            <div v-if="loading" class="text-center py-8">
                <div class="inline-block animate-spin">
                    <svg class="w-8 h-8 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <div v-else-if="demoUsers.length === 0" class="text-center py-8 text-gray-400">
                No demo users available. Please seed the database first.
            </div>

            <div v-else class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b border-gray-700">
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">Name</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">Email</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">Password</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">Balance</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">Transactions</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="user in demoUsers"
                        :key="user.id"
                        class="border-b border-gray-700 hover:bg-gray-700/50 transition cursor-pointer"
                        @click="fillLoginForm(user)"
                    >
                        <td class="px-4 py-3 text-white font-medium">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold mr-2">
                                    {{ user.initials }}
                                </div>
                                {{ user.name }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-300 font-mono text-xs">{{ user.email }}</td>
                        <td class="px-4 py-3 text-gray-400 font-mono text-xs">
                            <code class="bg-gray-900 px-2 py-1 rounded">password123</code>
                        </td>
                        <td class="px-4 py-3 text-white font-semibold">
                <span class="bg-green-900/30 text-green-300 px-3 py-1 rounded">
                  ${{ formatBalance(user.balance) }}
                </span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">{{ user.transactions }}</td>
                        <td class="px-4 py-3">
                            <button
                                @click.stop="fillLoginForm(user)"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition"
                            >
                                Use
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
 import { useAuthStore } from '@/stores/useAuthStore';
import apiClient from '@/api/client';
import { router, usePage } from '@inertiajs/vue3';
import type { LoginCredentials, DemoUser } from '@/types/api';
import axios, { AxiosError } from 'axios';
import { useAuth } from '@/composables/useAuth';

// const router = useRouter();
const authStore = useAuthStore();
const loading = ref<boolean>(true);
const error = ref<string>('');

const form = reactive<LoginCredentials>({
    email: 'john@example.com',
    password: 'password123',
});

const page = usePage();
const { storeToken } = useAuth();

// âœ… Store token in localStorage after login
onMounted(() => {
    const token = (page.props as any);
    if (token) {
        storeToken(token);
        console.log('âœ… Token stored in localStorage');
    }
});

const demoUsers = ref<DemoUser[]>([]);

const formatBalance = (balance: number): string => {
    return balance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const fillLoginForm = (user: DemoUser): void => {
    form.email = user.email;
    form.password = 'password123';
};

const getHighestBalanceUser = (): string => {
    if (demoUsers.value.length === 0) return 'N/A';
    const user = demoUsers.value.reduce((max, current) =>
        current.balance > max.balance ? current : max
    );
    return `${user.name} ($${formatBalance(user.balance)})`;
};

const getLowestBalanceUser = (): string => {
    if (demoUsers.value.length === 0) return 'N/A';
    const user = demoUsers.value.reduce((min, current) =>
        current.balance < min.balance ? current : min
    );
    return `${user.name} ($${formatBalance(user.balance)})`;
};

const getMostActiveUser = (): string => {
    if (demoUsers.value.length === 0) return 'N/A';
    const user = demoUsers.value.reduce((max, current) =>
        current.transactions > max.transactions ? current : max
    );
    return `${user.name} (${user.transactions} transactions)`;
};

const loadDemoUsers = async (): Promise<void> => {
    try {
        loading.value = true;
        const response = await apiClient.get('/demo-users');
        demoUsers.value = response.data.data;
    } catch (err) {
        console.error('Failed to load demo users:', err);
        error.value = 'Failed to load demo users';
    } finally {
        loading.value = false;
    }
};
const handleLogin = async (): Promise<void> => {
    await axios.get('/sanctum/csrf-cookie');

    loading.value = true;
    error.value = '';
     try {
        await authStore.login(form);
        await authStore.getCurrentUser();
     router.visit('/dashboard');
    } catch (err) {
        const axiosError = err as AxiosError<{ errors?: { email?: string[] } }>;
        error.value = axiosError.response?.data?.errors?.email?.[0] || 'Login failed. Please try again.';
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadDemoUsers();
});
</script>

<style scoped>
tr {
    transition: background-color 0.2s ease;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
