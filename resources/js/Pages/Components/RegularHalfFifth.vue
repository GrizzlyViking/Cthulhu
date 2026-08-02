<script setup>
import { computed } from 'vue';

const props = defineProps({
    skillValue: [Number, String],
    /** Adds a pressable affordance when the parent opens an editor on click. */
    interactive: {
        type: Boolean,
        default: false,
    },
});

const halfValue = computed(() => Math.ceil(props.skillValue / 2));
const fifthValue = computed(() => Math.ceil(props.skillValue / 5));
</script>

<template>
    <!--
        All three cells carry the same fill so the regular value reads as its own
        box against the parchment row behind it; the group's rounded corners give
        the leftmost and rightmost cells their rounded outer edge.
    -->
    <div
        class="tabular grid w-full max-w-[9rem] grid-cols-3 divide-x divide-cthulhu-green-800/15 overflow-hidden rounded-lg bg-parchment-200/70 text-center ring-1 ring-inset ring-cthulhu-green-800/25 transition"
        :class="interactive ? 'cursor-pointer hover:ring-2 hover:ring-cthulhu-yellow-500' : ''"
    >
        <div class="py-2 text-sm font-semibold text-cthulhu-green-900" title="Regular success">
            {{ skillValue }}
        </div>
        <div class="py-2 text-sm text-cthulhu-green-700" title="Hard success (half)">
            {{ halfValue }}
        </div>
        <div class="py-2 text-sm text-cthulhu-green-700" title="Extreme success (one fifth)">
            {{ fifthValue }}
        </div>
    </div>
</template>
