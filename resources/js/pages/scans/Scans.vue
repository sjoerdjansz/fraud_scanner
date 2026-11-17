<script setup lang="ts">

import AppLayout from "@/components/AppLayout.vue";
import type {Scan} from "@/types";
import scansRoutes from '@/routes/scans';
import {Link} from "@inertiajs/vue3";

defineProps<{ scans: Scan[] }>()

</script>

<template>
    <AppLayout>
        <header>
            <h1 class="text-2xl">All Scans</h1>
            <p v-if="!scans.length">No scans yet...</p>
        </header>
        <main class="relative scrollbar-thumb-only flex-1 overflow-auto">
            <ul class="flex flex-col gap-2">
                <li v-for="scan in scans" :key="scan.id" class="flex items-center justify-between bg-gray-100 rounded-md p-2"><p>Scan #<span>{{ scan.id }}</span> done at:
                    {{ new Date(scan.created_at).toLocaleString('nl-NL') }}</p>
                    <Link class="text-blue-700 cursor-pointer" :href="scansRoutes.show.url(scan.id)">View</Link>
                </li>
            </ul>
        </main>
    </AppLayout>
</template>

<style scoped>

</style>
