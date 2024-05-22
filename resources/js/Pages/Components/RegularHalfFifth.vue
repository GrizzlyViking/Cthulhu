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
        <input :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :disabled="!editable"
               class="text-center py-2 rounded-l-lg flex-initial w-12 text-gray-900 border border-r-0 border-cthulhu-green-400 bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 disabled:bg-gray-100">
        <div
            class="text-center py-3 flex-initial w-12 text-gray-900 border border-cthulhu-green-400 border-x-0 bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 read-only:bg-gray-100">
            {{ halfValue }}
        </div>
        <div
            class="text-center py-3 rounded-r-lg flex-initial w-12 text-gray-900 border border-cthulhu-green-400 bg-gray-50 text-xs read-only:bg-gray-100">
            {{ fifthValue }}
        </div>
    </div>
</template>
