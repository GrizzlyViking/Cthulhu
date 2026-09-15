<script setup>
import PlayerCard from "@/Pages/Components/PlayerCard.vue";
import { computed } from "vue";

const props = defineProps(['users'])

/* Copy before sorting: sorting the prop in place mutates the parent's array. */
const sortedUsers = computed(() =>
    [...(props.users ?? [])].sort((a, b) => a.name.localeCompare(b.name))
);
</script>

<template>
    <ul role="list" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <li
            v-for="player in sortedUsers"
            :key="player.id"
            class="card !p-0"
        >
            <player-card :player="player"></player-card>
        </li>
    </ul>
</template>
