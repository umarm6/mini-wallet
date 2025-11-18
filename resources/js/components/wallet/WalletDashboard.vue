<template>
    <div class="min-h-screen bg-gray-900 p-8">
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

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import TransferForm from './TransferForm.vue';
import TransactionHistory from './TransactionHistory.vue';
import Pusher from 'pusher-js';
import type { PusherTransactionEvent } from '@/types/api';
import { usePage } from '@inertiajs/vue3';
const historyComponent = ref<InstanceType<typeof TransactionHistory> | null>(null);

const currentUser = computed(() => usePage().props.auth.user);
const currentBalance = ref<string | number>(0);

const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};


onMounted(async () => {
    const authUser = usePage().props.auth.user;
     currentBalance.value = authUser?.balance || 0;

    // Initialize Pusher for real-time updates
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY as string, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER as string,
    });

    const channel = pusher.subscribe(`transaction.${authUser?.id}`);

    channel.bind('transaction.completed', (data: PusherTransactionEvent) => {
        // Update balance in real-time
        currentBalance.value = data.sender_update?.balance || data.receiver_update?.balance || currentBalance.value;

        // Reload transaction history
        if (historyComponent.value) {
            historyComponent.value.loadTransactions();
        }
    });
});
</script>
