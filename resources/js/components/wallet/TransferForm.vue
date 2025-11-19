<template>
    <div class=" p-8 rounded-lg border border-gray-300">
        <h2 class="text-2xl font-bold  mb-6">Send Money</h2>

        <div v-if="error" class="mb-4 p-4 bg-red-900/30 border border-red-700 rounded text-red-200">
            {{ error }}
        </div>

        <div v-if="success" class="mb-4 p-4 bg-green-900/30 border border-green-700 rounded text-green-800">
            {{ success }}
        </div>

        <form @submit.prevent="handleTransfer" class="space-y-4">
            <div>
                <label class="block  mb-2">Recipient User ID</label>
                <input
                    v-model.number="form.receiver_id"
                    type="number"
                    required
                    class="w-full px-4 py-2  border border-gray-600 rounded placeholder-gray-400 focus:outline-none focus:border-blue-500"
                    placeholder="Enter user ID"
                    min="1"
                />
                <p class="text-gray-400 text-sm mt-1">Your ID: {{ currentUser?.id }}</p>
            </div>

            <div>
                <label class="block  mb-2">Amount</label>
                <input
                    v-model.number="form.amount"
                    type="number"
                    required
                    step="0.01"
                    class="w-full px-4 py-2  border border-gray-600 rounded  placeholder-gray-400 focus:outline-none focus:border-blue-500"
                    placeholder="0.00"
                    min="0.01"
                />
                <div class="text-gray-400 text-sm mt-1">
                    <p>Amount to send: {{ formatCurrency(Number(form.amount) || 0) }}</p>
                    <p>Commission (1.5%): {{ formatCurrency(calculateCommission(Number(form.amount) || 0)) }}</p>
                    <p class="font-semibold text-white">
                        Total: {{ formatCurrency((Number(form.amount) || 0) + calculateCommission(Number(form.amount) || 0)) }}
                    </p>
                </div>
            </div>

            <button
                type="submit"
                :disabled="loading"
                class="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50  font-bold py-2 px-4 rounded mt-6 transition"
            >
                {{ loading ? 'Sending...' : 'Send Money' }}
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, computed } from 'vue';
import apiClient from '@/api/client';
import type { TransferRequest, TransferResponse } from '@/types/api';
import { AxiosError } from 'axios';
import { usePage } from '@inertiajs/vue3';

const loading = ref<boolean>(false);
const error = ref<string>('');
const success = ref<string>('');

const currentUser = computed(() => usePage().props.auth.user);


const form = reactive<TransferRequest>({
    receiver_id: 0,
    amount: 0,
});

const calculateCommission = (amount: number): number => {
    return amount * 0.015;
};

const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};

const handleTransfer = async (): Promise<void> => {
    loading.value = true;
    error.value = '';
    success.value = '';

    try {
        const response = await apiClient.post<TransferResponse>('/transactions', {
            receiver_id: form.receiver_id,
            amount: form.amount,
        });

        success.value = `Transfer successful! New balance: ${formatCurrency(Number(response.data.new_balance))}`;
        form.receiver_id = 0;
        form.amount = 0;

        // Emit event for parent component to refresh
        window.dispatchEvent(new CustomEvent('transactionCompleted'));
    } catch (err) {
        const axiosError = err as AxiosError<{ errors?: { amount?: string[] } }>;
        error.value = axiosError.response?.data?.errors?.amount?.[0] || 'Transfer failed. Please try again.';
    } finally {
        loading.value = false;
    }
};
</script>
