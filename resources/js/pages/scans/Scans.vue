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
        <div v-for="scan in scans" :key="scan.id">
            <p>Scan ID <span>{{ scan.id }}</span> done at: {{ new Date(scan.created_at).toLocaleString('nl-NL') }} </p>
            <Link class="text-blue-700 cursor-pointer" :href="scansRoutes.show.url(scan.id)">View Scan</Link>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
