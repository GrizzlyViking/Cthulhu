<script setup>
/*
 * Which eras a skill, weapon or piece of equipment belongs to.
 *
 * Most things belong to every era, so both boxes start ticked; untick one to
 * say a thing is of its time. Ticking none is refused server-side — it would
 * describe something no group could ever see — so the last box cannot be
 * cleared here either.
 */
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    /** [{ value, label, short }] as the server sends them. */
    eras: { type: Array, default: () => [] },
    legend: { type: String, default: 'Eras' },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const chosen = computed(() => new Set(props.modelValue ?? []));

/** The last remaining era stays ticked; there has to be one. */
const isLocked = (value) => chosen.value.has(value) && chosen.value.size === 1;

const toggle = (value) => {
    if (isLocked(value)) {
        return;
    }

    emit(
        'update:modelValue',
        props.eras
            .map((era) => era.value)
            .filter((era) => (era === value ? !chosen.value.has(era) : chosen.value.has(era)))
    );
};
</script>

<template>
    <fieldset>
        <legend class="field-label">{{ legend }}</legend>

        <div class="mt-1 flex flex-wrap gap-x-4 gap-y-2">
            <label
                v-for="era in eras"
                :key="era.value"
                class="inline-flex items-center gap-2 text-sm text-cthulhu-green-900"
                :class="disabled || isLocked(era.value) ? 'cursor-not-allowed' : 'cursor-pointer'"
                :title="isLocked(era.value) ? 'Something has to belong to at least one era.' : null"
            >
                <input
                    type="checkbox"
                    class="size-4 rounded border-parchment-400 text-cthulhu-green-600 focus:ring-cthulhu-green-600"
                    :checked="chosen.has(era.value)"
                    :disabled="disabled || isLocked(era.value)"
                    @change="toggle(era.value)"
                />
                {{ era.label }}
            </label>
        </div>

        <p v-if="hint" class="field-hint">{{ hint }}</p>
        <p v-if="error" class="field-error">{{ error }}</p>
    </fieldset>
</template>
