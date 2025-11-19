<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import WalletDashboard from '@/components/wallet/WalletDashboard.vue';
import { useAuth } from '@/composables/useAuth';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];


const page = usePage();
const { storeToken } = useAuth();

onMounted(() => {
    const token = (page.props as any);
    if (token) {
        storeToken(token);
    }
});

</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                <wallet-dashboard/>
            </div>
        </div>
    </AppLayout>
</template>
