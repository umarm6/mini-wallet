<template>
    <div class="bg-gray-800 p-8 rounded-lg border border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6">Transaction History</h2>

        <div v-if="transactions.length === 0" class="text-gray-400 text-center py-8">
            No transactions yet.
        </div>

        <div v-else class="space-y-4 max-h-96 overflow-y-auto">
            <div
                v-for="tx in transactions"
                :key="tx.id"
                class="bg-gray-700 p-4 rounded border border-gray-600"
                :class="getTransactionClass(tx)"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-300">
                            {{ tx.type === 'sent' ? 'Sent to user #' : 'Received from user #' }}
                            <span class="font-semibold">{{ tx.to_id || tx.from_id }}</span>
                        </p>
                        <p class="text-gray-500 text-sm">{{ formatDate(tx.created_at) }}</p>
                    </div>
                    <div class="text-right">
                        <p :class="tx.type === 'sent' ? 'text-red-400' : 'text-green-400'" class="font-semibold">
                            {{ tx.type === 'sent' ? '-' : '+' }}{{ formatCurrency(Number(tx.amount)) }}
                        </p>
                        <p v-if="tx.commission_fee" class="text-gray-500 text-sm">
                            Fee: {{ formatCurrency(Number(tx.commission_fee)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import apiClient from '@/api/client';
import type { Transaction, TransactionHistoryResponse } from '@/types/api';

const transactions = ref<Transaction[]>([]);

const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};

const formatDate = (date: string): string => {
    return new Date(date).toLocaleString();
};

const getTransactionClass = (tx: Transaction): string => {
    return tx.type === 'sent' ? 'border-red-600/30' : 'border-green-600/30';
};

const loadTransactions = async (): Promise<void> => {
    try {
        const response = await apiClient.get<TransactionHistoryResponse>('/transactions');
        transactions.value = response.data.data;
    } catch (err) {
        console.error('Failed to load transactions:', err);
    }
};

onMounted(() => {
    loadTransactions();

    // Listen for transfer complete events
    window.addEventListener('transferComplete', loadTransactions);
});

defineExpose({
    loadTransactions,
});
</script>
