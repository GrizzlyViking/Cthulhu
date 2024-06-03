<script setup>
import {computed} from "vue";

const props = defineProps(['modelValue', 'editable'])
const emit = defineEmits(['update:modelValue'])

const halfValue = computed(() => {
    return Math.ceil(props.modelValue / 2)
})
const fifthValue = computed(() => {
    return Math.ceil(props.modelValue / 5)
})
</script>

<template>
    <div class="grid grid-cols-3 divide-x border-1 justify-end max-w-36 align-middle">
        <input type="number"
                :value="props.modelValue"
               @input="emit('update:modelValue', $event.target.value)"
               :disabled="!editable"
               class="text-right p-0 rounded-l-lg w-12 text-gray-900 border border-r-0 border-cthulhu-green-400 bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100">
        <div
            class="text-center py-3 flex-initial w-12 text-gray-900 border border-cthulhu-green-400 bg-gray-50 text-xs read-only:bg-gray-100">
            {{ halfValue }}
        </div>
        <div
            class="text-center py-3 rounded-r-lg flex-initial w-12 text-gray-900 border border-cthulhu-green-400 bg-gray-50 text-xs read-only:bg-gray-100">
            {{ fifthValue }}
        </div>
    </div>
</template>
