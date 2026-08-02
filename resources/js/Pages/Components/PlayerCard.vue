<script setup>
import { ChatBubbleLeftRightIcon, CubeIcon, TrashIcon } from "@heroicons/vue/20/solid/index.js";
import Badge from "@/Pages/Components/Badge.vue";
import { computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";

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

const deleteUser = (user_id) => {
    if (confirm("Are you sure you want to delete this user?")) {
        router.delete(route('users.destroy', { user: user_id }));
    }
}

const updateUserRole = (user_id, new_role) => {
    router.put(
        route('users.role', { user: user_id }),
        { role: new_role }
    )
}

const firstName = computed(() => prop.player.name.replace(/^(\w+).*/, "$1"));

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
            <Badge>{{ player.role }}</Badge>
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

    <div class="grid grid-cols-2 divide-x divide-parchment-300 border-t border-parchment-300">
        <button
            v-if="player.role === 'player'"
            type="button"
            class="inline-flex flex-1 items-center justify-center px-2 py-3 text-sm font-semibold text-cthulhu-green-900 transition hover:bg-parchment-200"
            @click="updateUserRole(player.id, 'Keeper of Arcane Lore')"
        >
            Make {{ firstName }} Keeper
        </button>
        <button
            v-else
            type="button"
            class="inline-flex flex-1 items-center justify-center px-2 py-3 text-sm font-semibold text-cthulhu-green-900 transition hover:bg-parchment-200"
            @click="updateUserRole(player.id, 'player')"
        >
            Make {{ firstName }} a Player
        </button>

        <button
            type="button"
            class="inline-flex flex-1 items-center justify-center gap-2 px-2 py-3 text-sm font-semibold text-cthulhu-blood-400 transition hover:bg-cthulhu-blood-400/10"
            @click="deleteUser(player.id)"
        >
            <TrashIcon class="size-5 opacity-70" aria-hidden="true" />
            Delete
        </button>
    </div>
</template>
