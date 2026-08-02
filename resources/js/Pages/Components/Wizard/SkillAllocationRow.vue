<script setup>
import { computed } from 'vue';
import NumberField from '@/Pages/Components/Wizard/NumberField.vue';
import { fifth, half } from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    name: { type: String, required: true },
    base: { type: Number, required: true },
    otherPoints: { type: Number, default: 0 },
    note: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
    disabledReason: { type: String, default: '' },
    max: { type: Number, default: 99 },
});

const points = defineModel('points', { type: [Number, null], default: null });

const total = computed(() =>
    Math.min(99, props.base + (points.value ?? 0) + props.otherPoints)
);
</script>

<template>
    <div
        class="flex items-center justify-between gap-2 rounded-lg bg-cthulhu-green-100/60 px-3 py-2 ring-1 ring-cthulhu-green-300/60"
        :class="{ 'opacity-50': disabled }"
        :title="disabled ? disabledReason : undefined"
    >
        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-medium text-cthulhu-green-900">{{ name }}</p>
            <p class="text-[11px] text-cthulhu-green-700">
                base {{ base }}<template v-if="note"> · {{ note }}</template>
                <template v-if="otherPoints"> · +{{ otherPoints }} from other pool</template>
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <NumberField
                v-model="points"
                :min="0"
                :max="max"
                placeholder="0"
                width-class="w-14"
                :disabled="disabled"
            />
            <div class="w-20 text-center text-xs text-cthulhu-green-900">
                <span class="text-sm font-bold">{{ total }}</span>
                <span class="text-cthulhu-green-500"> / {{ half(total) }} / {{ fifth(total) }}</span>
            </div>
        </div>
    </div>
</template>
