<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
import scans from '@/routes/scans';
</script>

<template>
    <div class="flex h-dvh flex-col items-center bg-[#FDFDFC] text-[#1b1b18]">
        <div class="relative flex min-h-0 w-full max-w-[1280px] flex-1 flex-col gap-4 p-4">
            <nav class="flex gap-4">
                <Link class="cursoir-pointer text-blue-700 underline" :href="home.url()"
                    >New Scan</Link
                >
                <Link class="cursoir-pointer text-blue-700 underline" :href="scans.index.url()"
                    >All Scans</Link
                >
            </nav>
            <slot />
        </div>
    </div>
</template>

<style scoped></style>
