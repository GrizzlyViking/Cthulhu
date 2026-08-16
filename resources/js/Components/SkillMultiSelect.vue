<script setup>
/*
 * Picking several skills out of the seventy-odd on the list.
 *
 * Chips show what is chosen so the answer is readable at a glance; the search
 * box narrows the checkbox list below, which scrolls rather than growing — the
 * whole thing has to fit in a modal on a phone.
 */
import { computed, ref } from 'vue';
import { XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    /** [{ slug, label }] as the server sends them. */
    options: { type: Array, default: () => [] },
    label: { type: String, default: 'Skills' },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
    placeholder: { type: String, default: 'Search skills' },
});

const emit = defineEmits(['update:modelValue']);

const search = ref('');

const chosen = computed(() => new Set(props.modelValue ?? []));

const labelFor = (slug) =>
    props.options.find((option) => option.slug === slug)?.label ?? slug;

const chips = computed(() => (props.modelValue ?? []).map((slug) => ({ slug, label: labelFor(slug) })));

const visible = computed(() => {
    const term = search.value.trim().toLowerCase();

    if (term === '') return props.options;

    return props.options.filter(
        (option) =>
            option.label.toLowerCase().includes(term) || option.slug.toLowerCase().includes(term)
    );
});

const toggle = (slug) => {
    const next = chosen.value.has(slug)
        ? (props.modelValue ?? []).filter((entry) => entry !== slug)
        : [...(props.modelValue ?? []), slug];

    emit('update:modelValue', next);
};
</script>

<template>
    <fieldset>
        <legend class="field-label">{{ label }}</legend>

        <div v-if="chips.length" class="mt-1 flex flex-wrap gap-1">
            <button
                v-for="chip in chips"
                :key="chip.slug"
                type="button"
                class="chip-brass inline-flex items-center gap-1"
                :title="`Remove ${chip.label}`"
                @click="toggle(chip.slug)"
            >
                {{ chip.label }}
                <XMarkIcon class="size-3" aria-hidden="true" />
            </button>
        </div>

        <input v-model="search" type="search" class="field mt-2" :placeholder="placeholder" />

        <div class="mt-2 max-h-48 overflow-y-auto rounded-lg ring-1 ring-parchment-300 p-2">
            <div class="grid grid-cols-1 gap-x-4 gap-y-1 sm:grid-cols-2">
                <label
                    v-for="option in visible"
                    :key="option.slug"
                    class="inline-flex cursor-pointer items-center gap-2 text-sm text-cthulhu-green-900"
                >
                    <input
                        type="checkbox"
                        class="size-4 rounded border-parchment-400 text-cthulhu-green-600 focus:ring-cthulhu-green-600"
                        :checked="chosen.has(option.slug)"
                        @change="toggle(option.slug)"
                    />
                    {{ option.label }}
                </label>
            </div>

            <p v-if="visible.length === 0" class="py-2 text-center text-sm text-cthulhu-green-500">
                No skill matches “{{ search }}”.
            </p>
        </div>

        <p v-if="hint" class="field-hint">{{ hint }}</p>
        <p v-if="error" class="field-error">{{ error }}</p>
    </fieldset>
</template>
