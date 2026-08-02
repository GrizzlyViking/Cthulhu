<script setup>
const model = defineModel({ type: [Number, String, null] });

defineProps({
    min: { type: Number, default: 0 },
    max: { type: Number, default: 99 },
    placeholder: { type: String, default: '' },
    widthClass: { type: String, default: 'w-20' },
    disabled: { type: Boolean, default: false },
});

const onInput = (event) => {
    const value = event.target.value;
    model.value = value === '' ? null : Number(value);
};
</script>

<template>
    <input
        type="number"
        :value="model"
        @input="onInput"
        inputmode="numeric"
        :min="min"
        :max="max"
        :placeholder="placeholder"
        :disabled="disabled"
        autocomplete="off"
        data-1p-ignore
        data-lpignore="true"
        data-bwignore
        class="no-spinner rounded-md border-0 py-1.5 text-center text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600 disabled:opacity-40"
        :class="widthClass"
    />
</template>

<style scoped>
.no-spinner::-webkit-outer-spin-button,
.no-spinner::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.no-spinner {
    -moz-appearance: textfield;
    appearance: textfield;
}
</style>
