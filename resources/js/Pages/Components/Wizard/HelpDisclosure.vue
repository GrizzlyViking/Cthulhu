<script setup>
import { ref } from 'vue';
import { QuestionMarkCircleIcon, XMarkIcon } from '@heroicons/vue/20/solid';

defineProps({
    label: { type: String, default: 'Help' },
});

const open = ref(false);
</script>

<template>
    <div class="inline-block w-full">
        <button
            type="button"
            @click="open = !open"
            class="inline-flex items-center gap-1 text-xs font-medium text-cthulhu-green-600 hover:text-cthulhu-green-800"
            :aria-expanded="open"
        >
            <component :is="open ? XMarkIcon : QuestionMarkCircleIcon" class="size-4" aria-hidden="true" />
            <span>{{ open ? 'Hide' : label }}</span>
        </button>
        <div
            v-if="open"
            class="mt-2 rounded-lg bg-cthulhu-green-100 p-3 text-xs leading-5 text-cthulhu-green-700 ring-1 ring-cthulhu-green-300"
        >
            <slot />
        </div>
    </div>
</template>
