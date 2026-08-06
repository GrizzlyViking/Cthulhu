<script setup>
import { ChatBubbleLeftRightIcon, CubeIcon } from "@heroicons/vue/20/solid/index.js";
import Badge from "@/Pages/Components/Badge.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

let prop = defineProps({ player: Object })

let page = usePage();

const addPlayerToMessagesList = (player) => {
    if (page.props.auth.listOfMessageUsers.indexOf(player) === -1) {
        page.props.auth.listOfMessageUsers.push(player);
    } else {
        let index = page.props.auth.listOfMessageUsers.indexOf(player);
        page.props.auth.listOfMessageUsers.splice(index, 1);
    }
};

const playerSelectedForMessage = computed(() => {
    return (player) => {
        return page.props.auth.listOfMessageUsers.indexOf(player) !== -1
    }
});

const playerSelectedForRoll = computed(() => {
    return (player) => {
        return page.props.auth.listOfRollUsers.indexOf(player) !== -1
    }
});

const addPlayerToRollList = (player) => {
    if (page.props.auth.listOfRollUsers.indexOf(player) === -1) {
        page.props.auth.listOfRollUsers.push(player);
    } else {
        let index = page.props.auth.listOfRollUsers.indexOf(player);
        page.props.auth.listOfRollUsers.splice(index, 1);
    }
}

const toggleClass = (isSelected) => [
    'inline-flex flex-1 items-center justify-center gap-2 px-2 py-3 text-sm font-semibold transition',
    isSelected
        ? 'bg-cthulhu-green-800 text-parchment-100'
        : 'text-cthulhu-green-900 hover:bg-parchment-200',
];
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

    <div class="grid grid-cols-2 divide-x divide-parchment-300 border-t border-parchment-300">
        <button type="button" :class="toggleClass(playerSelectedForMessage(player))" @click="addPlayerToMessagesList(player)">
            <ChatBubbleLeftRightIcon class="size-5 opacity-70" aria-hidden="true" />
            Message
        </button>
        <button type="button" :class="toggleClass(playerSelectedForRoll(player))" @click="addPlayerToRollList(player)">
            <CubeIcon class="size-5 opacity-70" aria-hidden="true" />
            Roll
        </button>
    </div>

</template>
