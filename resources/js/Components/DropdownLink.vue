<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-cthulhu-green-900 transition hover:bg-parchment-200 focus:bg-parchment-200 focus:outline-none"
    >
        <slot />
    </Link>
</template>
