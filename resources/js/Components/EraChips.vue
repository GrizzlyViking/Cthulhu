<script setup>
/*
 * The eras something belongs to, at a glance.
 *
 * Belonging to every era is the ordinary case and says nothing worth a chip,
 * so nothing is drawn for it — the chips are there to mark what is of its time.
 */
import { computed } from 'vue';

const props = defineProps({
    eras: { type: Array, default: () => [] },
    /** [{ value, label, short }] as the server sends them. */
    options: { type: Array, default: () => [] },
    /** Draw the chips even when a thing belongs everywhere. */
    always: { type: Boolean, default: false },
});

const isEverywhere = computed(
    () => props.options.length > 0 && (props.eras ?? []).length >= props.options.length
);

const chips = computed(() => {
    if (isEverywhere.value && !props.always) {
        return [];
    }

    return props.options.filter((option) => (props.eras ?? []).includes(option.value));
});
</script>

<template>
    <span v-if="chips.length" class="inline-flex flex-wrap items-center gap-1">
        <span v-for="era in chips" :key="era.value" class="chip" :title="`Available in ${era.label}`">
            {{ era.short }}
        </span>
    </span>
</template>
