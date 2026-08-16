<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FlashMessages from '@/Components/FlashMessages.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-cthulhu-green-950 px-4 pt-10 sm:justify-center sm:pt-0">
        <Link href="/" class="rounded-lg p-2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
            <ApplicationLogo class="size-20 fill-current text-cthulhu-yellow-400" />
        </Link>

        <div class="w-full empty:hidden sm:max-w-md [&>*]:mt-6">
            <FlashMessages />
        </div>

        <div class="panel mt-6 w-full px-6 py-5 sm:max-w-md">
            <slot />
        </div>
    </div>
</template>
