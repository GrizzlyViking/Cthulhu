<script setup>
import Badge from "@/Pages/Components/Badge.vue";

defineProps({ player: Object })
</script>

<template>
    <div class="flex items-center justify-between gap-3 p-4">
        <h3 class="truncate text-sm font-semibold text-cthulhu-green-900">{{ player.name }}</h3>
        <div class="flex shrink-0 items-center gap-2">
            <span
                class="size-2 rounded-full"
                :class="player.isOnline ? 'bg-cthulhu-green-350' : 'bg-cthulhu-green-500/40'"
                :title="player.isOnline ? 'Online' : 'Offline'"
            ></span>
            <Badge v-for="role in player.role_names" :key="role">{{ role }}</Badge>
        </div>
    </div>
</template>
