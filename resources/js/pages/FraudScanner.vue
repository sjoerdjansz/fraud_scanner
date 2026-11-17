<script setup lang="ts">
import {Head, router} from '@inertiajs/vue3';
import {ref} from "vue";
import FraudScanController from "@/actions/App/Http/Controllers/FraudScanController";
import AppLayout from "@/components/AppLayout.vue";

defineProps<{
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
    <AppLayout>
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
    </AppLayout>
</template>
