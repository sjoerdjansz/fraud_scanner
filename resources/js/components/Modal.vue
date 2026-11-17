<script setup lang="ts">
import {XCircleIcon} from "@heroicons/vue/24/outline";

import type {Customer} from "@/types";

// TODO: customer data laden in modal

defineProps<{customer: Customer}>()

const emit = defineEmits<{close: []}>()



</script>

<template>
    <div @click.self="emit('close')" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
    <div class="w-100 h-120 bg-gray-200 rounded-md drop-shadow-md top-0 mt-46 p-4">
        <div>
            <div class="flex items-center justify-between">
            <h2 class="text-xl">Customer Details</h2>
            <XCircleIcon @click="emit('close')" class="h-6 cursor-pointer"/>
            </div>
            <hr class="h-px my-6 bg-gray-300 border-0">
        </div>
        <ul>
            <li>ID: {{ customer.id }}</li>
            <li>First name: {{ customer.first_name }}</li>
            <li>Last name: {{ customer.last_name }}</li>
            <li>Date of birth: {{ customer.date_of_birth }}</li>
            <li>Phone: {{ customer.phone }}</li>
            <li>IBAN: {{ customer.iban }}</li>
            <li>IP: {{ customer.ip }}</li>
            <li v-if="customer.fraud_reasons.length" class="text-red-600">Fraudulent: {{ customer.fraud_reasons.join(',') }}</li>
        </ul>
    </div>
</div>
</template>

<style scoped>

</style>
