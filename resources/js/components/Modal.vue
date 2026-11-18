<script setup lang="ts">
import { XCircleIcon } from '@heroicons/vue/24/outline';

import type { Customer } from '@/types';

defineProps<{ customer: Customer }>();

const emit = defineEmits<{ close: [] }>();
</script>

<template>
    <div
        @click.self="emit('close')"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
    >
        <div
            class="top-0 mt-46 h-120 w-140 overflow-auto rounded-md bg-gray-200 p-4 drop-shadow-md"
        >
            <div>
                <div class="flex items-center justify-between">
                    <h2 class="text-xl">Customer Details</h2>
                    <button @click="emit('close')">
                        <XCircleIcon class="h-6 cursor-pointer" />
                    </button>
                </div>
                <hr class="my-6 h-px border-0 bg-gray-300" />
            </div>
            <ul>
                <li>
                    <span class="font-bold">Customer id:</span>
                    {{ customer.external_customer_id ?? '-' }}
                </li>
                <li><span class="font-bold">First name:</span> {{ customer.first_name ?? '-' }}</li>
                <li><span class="font-bold">Last name:</span> {{ customer.last_name ?? '-' }}</li>
                <li>
                    <span class="font-bold">Date of birth:</span>
                    {{ customer.date_of_birth ?? '-' }}
                </li>
                <li><span class="font-bold">Phone:</span> {{ customer.phone ?? '-' }}</li>
                <li><span class="font-bold">IBAN:</span> {{ customer.iban ?? '-' }}</li>
                <li><span class="font-bold">IP:</span> {{ customer.ip ?? '-' }}</li>
                <li class="capitalize" v-if="customer.products?.length">
                    <span class="font-bold">Products:</span>
                    {{ customer.products.join(', ') }}
                </li>
                <li class="text-red-600" v-if="customer.fraud_reason_labels.length">
                    <ul class="rounded-md bg-red-200 px-2 py-4">
                        <li v-for="label in customer.fraud_reason_labels" :key="label">
                            <span class="font-bold">Fraudulent:</span> {{ label }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped></style>
