<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';
import AppLayout from '@/components/AppLayout.vue';
import type { Customer } from '@/types';
import Modal from '@/components/Modal.vue';
import { ref } from 'vue';

defineProps<{
    customers: Customer[];
    scan: { id: number; created_at: string };
}>();

const selectedCustomer = ref<Customer | null>(null);
</script>

<template>
    <AppLayout>
        <Head :title="`Scan #${scan.id}`" />
        <Modal
            v-if="selectedCustomer"
            :customer="selectedCustomer"
            @close="selectedCustomer = null"
        />
        <header class="sticky top-0">
            <h1 class="text-2xl">Scan {{ scan.id }}</h1>
            <p>Done at {{ new Date(scan.created_at).toLocaleString('nl-NL') }}</p>
        </header>
        <main class="relative scrollbar-thumb-only overflow-auto flex-1 min-h-0">
            <table
                class="w-full border-separate border-spacing-y-2 text-left whitespace-nowrap [&_th]:bg-gray-800 [&_th]:px-3 [&_th]:py-2 [&_th]:text-white [&_tr>*:first-child]:rounded-l-sm [&_tr>*:last-child]:rounded-r-sm"
            >
                <thead class="sticky top-0">
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Date of birth</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>IBAN</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        @click="selectedCustomer = customer"
                        class="cursor-pointer"
                        v-for="customer in customers"
                        :key="customer.id"
                        :class="
                            customer.fraud_reasons?.length
                                ? 'bg-red-200 hover:bg-red-300'
                                : 'bg-gray-100 hover:bg-gray-200'
                        "
                    >
                        <td>
                            <span v-if="customer.fraud_reasons?.length"
                                ><ExclamationTriangleIcon class="h-6 rounded-md text-red-600"
                            /></span>
                        </td>
                        <td>{{ customer.external_customer_id ?? ' ' }}</td>
                        <td>{{ customer.first_name ?? ' ' }}</td>
                        <td><span :title="customer.last_name ?? ' '" class="block max-w-40 truncate">{{ customer.last_name ?? ' ' }}</span></td>
                        <td>{{ customer.date_of_birth?.slice(0, 10) ?? ' ' }}</td>
                        <td>{{ customer.email ?? ' ' }}</td>
                        <td>{{ customer.phone ?? ' ' }}</td>
                        <td>{{ customer.iban ?? ' ' }}</td>
                        <td>{{ customer.ip ?? ' ' }}</td>
                    </tr>
                    <tr v-if="!customers.length">
                        <td colspan="9">No customer data in this scan</td>
                    </tr>
                </tbody>
            </table>
        </main>
    </AppLayout>
</template>

<style scoped></style>
