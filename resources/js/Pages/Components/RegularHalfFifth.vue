<script setup>
import {computed} from "vue";

const props = defineProps(['skillValue'])

const halfValue = computed(() => {
    return Math.ceil(props.skillValue / 2)
})
const fifthValue = computed(() => {
    return Math.ceil(props.skillValue / 5)
})
</script>

<template>
    <div class="grid grid-cols-3 divide-x border-1 justify-end max-w-36 align-middle">
        <div
            class="text-center py-3 rounded-l-lg flex-initial w-12 text-gray-900 border-y border-l border-cthulhu-green-400 bg-gray-50 text-xs read-only:bg-gray-100"
        >
            {{ skillValue }}
        </div>
        <div
            class="text-center py-3 flex-initial w-12 text-gray-900 border border-cthulhu-green-400 bg-gray-50 text-xs read-only:bg-gray-100"
        >
            {{ halfValue }}
        </div>
        <div
            class="text-center py-3 rounded-r-lg flex-initial w-12 text-gray-900 border-y border-r border-cthulhu-green-400 bg-gray-50 text-xs read-only:bg-gray-100">
            {{ fifthValue }}
        </div>
    </div>
</template>
