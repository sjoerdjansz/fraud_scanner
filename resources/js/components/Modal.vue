<script setup lang="ts">
import { XCircleIcon } from '@heroicons/vue/24/outline';

import type { Customer } from '@/types';

// TODO: customer data laden in modal

defineProps<{ customer: Customer }>();

const emit = defineEmits<{ close: [] }>();
</script>

<template>
    <div
        @click.self="emit('close')"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
    >
        <div class="top-0 mt-46 h-120 w-100 rounded-md bg-gray-200 p-4 drop-shadow-md">
            <div>
                <div class="flex items-center justify-between">
                    <h2 class="text-xl">Customer Details</h2>
                    <XCircleIcon @click="emit('close')" class="h-6 cursor-pointer" />
                </div>
                <hr class="my-6 h-px border-0 bg-gray-300" />
            </div>
            <ul>
                <li>ID: {{ customer.id }}</li>
                <li>First name: {{ customer.first_name }}</li>
                <li>Last name: {{ customer.last_name }}</li>
                <li>Date of birth: {{ customer.date_of_birth }}</li>
                <li>Phone: {{ customer.phone }}</li>
                <li>IBAN: {{ customer.iban }}</li>
                <li>IP: {{ customer.ip }}</li>
                <li v-if="customer.fraud_reasons.length" class="text-red-600">
                    Fraudulent: {{ customer.fraud_reasons.join(',') }}
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped></style>
