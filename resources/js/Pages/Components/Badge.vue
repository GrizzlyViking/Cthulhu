<script setup>
defineProps({badgeClass: Object})
</script>

<template>
    <span
        :class="badgeClass"
        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
    ><slot></slot></span>
</template>
