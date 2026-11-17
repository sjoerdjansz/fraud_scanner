<script setup lang="ts">
import {XCircleIcon} from "@heroicons/vue/24/outline";

import type {Customer} from "@/types";

// TODO: customer data laden in modal

</script>

<template>
    <div class="absolute w-100 h-120 z-50 bg-gray-200 rounded-md drop-shadow-md top-0 mt-46 p-4">
        <div>
            <div class="flex items-center justify-between">
            <h2 class="text-xl">Customer Details</h2>
            <XCircleIcon class="h-6 cursor-pointer"/>
            </div>
            <hr class="h-px my-6 bg-gray-300 border-0">
        </div>
        <ul>
            <li>Customer info hier...</li>
        </ul>
    </div>

</template>

<style scoped>

</style>
