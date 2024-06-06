<script setup>
import PlayerCard from "@/Pages/Components/PlayerCard.vue";
const props = defineProps(['users', 'selectedForMessage'])

const emit = defineEmits([
    "message",
    "roll"
]);
</script>

<template>
    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <li v-for="player in props.users" :key="player.id"
            class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow"
            :class="{inactive: !player.isOnline}"
        >
            <player-card
                :player="player"
                :selected-for-message="props.selectedForMessage"
            ></player-card>
        </li>
    </ul>
</template>

<style scoped>
.inactive {
    opacity: 0.5;
}
</style>
