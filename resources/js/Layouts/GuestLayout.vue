<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashMessages from '@/Components/FlashMessages.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="app-canvas relative isolate flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <img src="/images/cthulhu_man_reading.jpeg" alt="" class="absolute inset-0 -z-20 size-full object-cover opacity-20" />
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-cthulhu-green-950 to-cthulhu-green-950/60" aria-hidden="true"></div>
        <Link href="/" aria-label="Ages of Madness home" class="flex flex-col items-center gap-3 rounded-lg p-2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
            <ApplicationLogo class="size-14 fill-current text-cthulhu-yellow-400" aria-hidden="true" />
            <span class="display text-2xl text-parchment-100">Ages of Madness</span>
        </Link>

        <div class="w-full max-w-md empty:hidden [&>*]:mt-6">
            <FlashMessages />
        </div>

        <main class="panel mt-8 w-full max-w-md border-t-2 border-cthulhu-yellow-500 px-6 py-7 sm:px-8">
            <slot />
        </main>
        <p class="mt-6 text-xs text-cthulhu-green-200">Call of Cthulhu · Investigator archive</p>
    </div>
</template>
