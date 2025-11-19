<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import TransferForm from './TransferForm.vue';
import TransactionHistory from './TransactionHistory.vue';
import Pusher from 'pusher-js';
import type { User } from '@/types/api';
import { usePage } from '@inertiajs/vue3';
import apiClient from '@/api/client';

const historyComponent = ref<InstanceType<typeof TransactionHistory> | null>(null);
const currentUser = computed(() => usePage().props.auth.user);
const currentBalance = ref<string | number>(0);

const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};

const loadWallet = async (): Promise<void> => {
    try {
        const response = await apiClient.get<User>('/me');
        currentBalance.value = response.data.balance;

    } catch (err) {
        console.error('Failed to load transactions:', err);
    }
};

onMounted(async () => {
    const authUser = usePage().props.auth.user;
    currentBalance.value = authUser?.balance || 0;

    // Initialize Pusher for real-time updates
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY as string, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER as string,
    });

    const channel = pusher.subscribe(`transaction_user_${authUser?.id}`);


    channel.bind('transactionCompleted', (data: any) => {
        console.log('ðŸ“¨ Pusher event received:', data);
        // âœ… Use correct field names
        if (data.transaction.sender_id === authUser?.id) {
            currentBalance.value = data.sender_balance;
            console.log('âœ… Sent money! New balance:', data.sender_balance);
        } else if (data.transaction.receiver_id === authUser?.id) {
            currentBalance.value = data.receiver_balance;
            console.log('âœ… Received money! New balance:', data.receiver_balance);
        }

        // Reload transaction history
        if (historyComponent.value) {
            historyComponent.value.loadTransactions();
        }
    });


    window.addEventListener('transactionCompleted', loadWallet);

});
</script>

<template>
    <div class="min-h-screen p-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-white">Mini Wallet</h1>
            </div>

            <!-- Balance Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-8 mb-8 border border-blue-500">
                <p class="text-blue-200 text-lg mb-2">Current Balance</p>
                <h2 class="text-5xl font-bold text-white">{{ formatCurrency(Number(currentBalance)) }}</h2>
                <p class="text-blue-200 text-sm mt-4">{{ currentUser?.email }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Transfer Form -->
                <TransferForm />

                <!-- Transaction History -->
                <TransactionHistory ref="historyComponent" />
            </div>
        </div>
    </div>
</template>
