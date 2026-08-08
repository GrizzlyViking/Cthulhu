<script setup>
/*
 * A folded-up heading that opens on a tap.
 *
 * Used by the weapon and equipment pickers, where the catalogue is far too long
 * to scroll through on a phone: the categories arrive closed, and choosing one
 * is how you narrow it down. Open state belongs to the parent, so a search can
 * fold everything out at once.
 */
import { useId } from 'vue';
import { ChevronRightIcon } from '@heroicons/vue/20/solid';

defineProps({
    title: { type: String, required: true },
    /** How many things are inside, shown on the closed heading. */
    count: { type: Number, default: null },
    /** A second line under the title — a hint at what is in there. */
    subtitle: { type: String, default: null },
    open: { type: Boolean, default: false },
});

defineEmits(['toggle']);

const panelId = useId();
</script>

<template>
    <div class="overflow-hidden rounded-lg ring-1 ring-parchment-300">
        <button
            type="button"
            class="flex w-full items-center gap-3 bg-parchment-200/70 px-3 py-3 text-left transition hover:bg-parchment-200 focus-visible:outline focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-cthulhu-green-800"
            :aria-expanded="open"
            :aria-controls="panelId"
            @click="$emit('toggle')"
        >
            <ChevronRightIcon
                class="size-5 shrink-0 text-cthulhu-green-500 transition-transform duration-200"
                :class="open ? 'rotate-90' : ''"
                aria-hidden="true"
            />

            <span class="min-w-0 flex-auto">
                <span class="block truncate text-sm font-semibold text-cthulhu-green-900">{{ title }}</span>
                <span v-if="subtitle" class="block truncate text-xs text-cthulhu-green-500">{{ subtitle }}</span>
            </span>

            <span v-if="count !== null" class="chip tabular shrink-0">{{ count }}</span>
        </button>

        <!-- 0fr → 1fr animates the fold without having to measure anything. -->
        <div
            :id="panelId"
            class="grid transition-[grid-template-rows] duration-200 ease-out motion-reduce:transition-none"
            :class="open ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
        >
            <div class="overflow-hidden">
                <div class="border-t border-parchment-300 bg-parchment-50/60 p-2">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
