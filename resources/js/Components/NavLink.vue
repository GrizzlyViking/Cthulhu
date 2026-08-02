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
        'relative rounded-md px-3 py-2 text-sm font-medium transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500',
        props.active
            ? 'bg-cthulhu-green-800 text-cthulhu-yellow-400'
            : 'text-cthulhu-green-200 hover:bg-cthulhu-green-800 hover:text-parchment-100',
    ].join(' ')
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
