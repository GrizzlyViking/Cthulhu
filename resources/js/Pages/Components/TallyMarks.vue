<script setup>
import { computed } from 'vue';

const props = defineProps({
    /** Marks to draw. Anything below one draws nothing at all. */
    count: { type: Number, default: 0 },
});

/**
 * Tallies are drawn five to a group — four uprights struck through by a
 * diagonal — so a glance gives the figure without counting every stroke.
 * The marks scale with the surrounding font size and take their colour from
 * it, so the caller decides how loud they are.
 *
 * @type {import('vue').ComputedRef<Array<{uprights: number[], struck: boolean, width: number}>>}
 */
const groups = computed(() => {
    const total = Math.max(0, Math.floor(props.count));
    const drawn = [];

    for (let done = 0; done < total; done += 5) {
        const size = Math.min(5, total - done);
        const uprights = Math.min(size, 4);

        drawn.push({
            uprights: Array.from({ length: uprights }, (_, index) => 2 + index * 4),
            struck: size === 5,
            width: size === 5 ? 18 : uprights * 4,
        });
    }

    return drawn;
});

const label = computed(() => `${props.count} experience ${props.count === 1 ? 'check' : 'checks'}`);
</script>

<template>
    <span class="inline-flex items-center gap-[0.3em]" role="img" :aria-label="label">
        <svg
            v-for="(group, index) in groups"
            :key="index"
            :viewBox="`0 0 ${group.width} 16`"
            class="block h-[1em] w-auto"
            fill="none"
            stroke="currentColor"
            stroke-width="1.75"
            stroke-linecap="round"
            aria-hidden="true"
        >
            <line v-for="x in group.uprights" :key="x" :x1="x" y1="1.5" :x2="x" y2="14.5" />
            <line v-if="group.struck" x1="0.75" y1="14" x2="17.25" y2="2" />
        </svg>
    </span>
</template>
