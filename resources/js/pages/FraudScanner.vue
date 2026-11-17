<script setup lang="ts">
import {Head, router} from '@inertiajs/vue3';
import {ref} from "vue";
import FraudScanController from "@/actions/App/Http/Controllers/FraudScanController";

type Customer = {
    external_customer_id: number | null;
    first_name: string | null;
    last_name: string | null;
    date_of_birth: string | null;
    email: string | null;
    phone: string | null;
    ip: string | null;
    iban: string | null;
};

defineProps<{
    customers?: Customer[];
    errors: Record<string, string>;
}>();

const isScanning = ref(false);

function startFraudeScan() {
    router.post(FraudScanController.url(), {}, {
        onStart: () => (isScanning.value = true),
        onFinish: () => (isScanning.value = false),
    })
}


</script>

<template>
    <Head title="Fraud Scanner"/>
    <div class="flex h-dvh flex-col items-center bg-[#FDFDFC] text-[#1b1b18]">
        <div class="flex w-full max-w-[1024px] flex-1 flex-col gap-4 overflow-hidden p-4">
            <header class="flex w-full flex-col gap-4 rounded-md bg-gray-100 p-6 shadow-2xs">
                <div class="flex items-center justify-between gap-4">
                    <h1 class="text-2xl">Fraud Scanner</h1>
                    <button :disabled="isScanning" @click="startFraudeScan"
                            class="shrink-0 cursor-pointer rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-600 disabled:cursor-not-allowed"
                    >
                        {{ isScanning ? 'Hold your horses...' : 'Get Customers' }}
                    </button>
                </div>
                <p>This applications scans all customers and highlights the fraudulent ones.</p>
                <p v-if="errors.scan" class="text-red-600 p-2">{{ errors.scan }}</p>
            </header>
            <main class="relative scrollbar-thumb-only flex-1 overflow-auto">
                <table
                    class="w-full border-separate border-spacing-y-2 text-left whitespace-nowrap  [&_th]:bg-gray-800 [&_th]:px-3 [&_th]:py-2 [&_th]:text-white [&_tr>*:first-child]:rounded-l-sm [&_tr>*:last-child]:rounded-r-sm"
                >
                    <thead class="sticky top-0">
                    <tr>
                        <th>ID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Phone</th>
                        <th>Date of birth</th>
                        <th>Email</th>
                        <th>IBAN</th>
                        <th>IP</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-gray-100" v-for="(customer, i) in customers ?? []" :key="i">
                        <td>{{ customer.external_customer_id ?? ' ' }}</td>
                        <td>{{ customer.first_name ?? ' ' }}</td>
                        <td>{{ customer.last_name ?? ' ' }}</td>
                        <td>{{ customer.date_of_birth?.slice(0, 10) ?? ' ' }}</td>
                        <td>{{ customer.email ?? ' ' }}</td>
                        <td>{{ customer.phone ?? ' ' }}</td>
                        <td>{{ customer.iban ?? ' ' }}</td>
                        <td>{{ customer.ip ?? ' ' }}</td>
                    </tr>
                    <tr v-if="!customers?.length">
                        <td colspan="8">Start a scan to get customer data</td>
                    </tr>

                    </tbody>
                </table>
            </main>
        </div>
    </div>
</template>
