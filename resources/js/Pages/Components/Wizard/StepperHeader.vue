<script setup>
import { CheckIcon } from '@heroicons/vue/20/solid';

defineProps({
    steps: { type: Array, required: true },
    current: { type: Number, required: true },
    furthest: { type: Number, required: true },
});

const emit = defineEmits(['select']);
</script>

<template>
    <nav
        aria-label="Progress"
        class="sticky top-0 z-10 rounded-xl bg-parchment-100/95 px-2 py-2 shadow-card ring-1 ring-cthulhu-green-900/15 backdrop-blur"
    >
        <ol class="flex gap-1 overflow-x-auto sm:justify-between">
            <li v-for="(step, index) in steps" :key="step" class="shrink-0">
                <button
                    type="button"
                    :disabled="index > furthest"
                    @click="emit('select', index)"
                    class="flex items-center gap-1.5 rounded-full px-2.5 py-1.5 text-xs font-semibold transition"
                    :class="[
                        index === current
                            ? 'bg-cthulhu-green-800 text-white'
                            : index <= furthest
                                ? 'text-cthulhu-green-800 hover:bg-cthulhu-green-300/60'
                                : 'text-cthulhu-green-500 cursor-not-allowed',
                    ]"
                    :aria-current="index === current ? 'step' : undefined"
                >
                    <span
                        class="flex size-5 shrink-0 items-center justify-center rounded-full border text-[10px]"
                        :class="[
                            index === current
                                ? 'border-white/70'
                                : index < furthest || (index < current)
                                    ? 'border-cthulhu-green-600 bg-cthulhu-green-600 text-white'
                                    : 'border-current',
                        ]"
                    >
                        <CheckIcon v-if="index < furthest && index !== current" class="size-3" aria-hidden="true" />
                        <template v-else>{{ index + 1 }}</template>
                    </span>
                    <span class="whitespace-nowrap">{{ step }}</span>
                </button>
            </li>
        </ol>
    </nav>
</template>
