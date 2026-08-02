<script setup>
defineProps({badgeClass: Object})
</script>

<template>
    <span
        :class="badgeClass"
        class="chip"
    ><slot></slot></span>
</template>
