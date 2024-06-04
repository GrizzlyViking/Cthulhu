<script setup>
import PlayerCard from "@/Pages/Components/PlayerCard.vue";
defineProps(['users'])
</script>

<template>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <li v-for="player in users" :key="player.id"
            class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
            <player-card :player="player"></player-card>
        </li>
    </ul>
</template>
