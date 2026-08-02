<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    [
        'block w-full border-l-4 py-2.5 pe-4 ps-4 text-start text-base font-medium transition focus:outline-none',
        props.active
            ? 'border-cthulhu-yellow-500 bg-cthulhu-green-800 text-cthulhu-yellow-400'
            : 'border-transparent text-cthulhu-green-200 hover:border-cthulhu-green-300 hover:bg-cthulhu-green-800 hover:text-parchment-100',
    ].join(' ')
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
