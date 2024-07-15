<script setup>
import {ChatBubbleLeftRightIcon, CubeIcon, TrashIcon} from "@heroicons/vue/20/solid/index.js";
import Badge from "@/Pages/Components/Badge.vue";
import {computed, ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
let prop = defineProps({player: Object})

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
        router.delete(route('users.destroy', {user: user_id}));
    }
}

const updateUserRole = (user_id, new_role) => {
    router.put(
        route('users.role', {user: user_id}),
        { role: new_role }
    )
}
</script>

<template>
    <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="truncate text-sm font-medium text-gray-900">{{ player.name }}</h3>
                <badge :badge-class="{ 'text-xs': true }">{{ player.role }}</badge>
            </div>
        </div>
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <div class="flex w-0 flex-1">
                <a @click="addPlayerToMessagesList(player)"
                   class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                   :class="{'bg-cthulhu-green-200': playerSelectedForMessage(player)}"
                >
                    <ChatBubbleLeftRightIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    Message
                </a>
            </div>
            <div class="-ml-px flex w-0 flex-1">
                <a @click="addPlayerToRollList(player)"
                   class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                   :class="{'bg-cthulhu-green-200': playerSelectedForRoll(player)}"
                >
                    <CubeIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    Roll
                </a>
            </div>
        </div>
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <div v-if="player.role === 'player'" class="flex w-0 flex-1">
                <a @click="updateUserRole(player.id, 'Keeper of Arcane Lore')"
                   class="relative inline-flex w-0 flex-1 items-center justify-center rounded-bl-lg border border-transparent py-4 px-2 text-sm font-semibold text-gray-900"
                >
                    Make {{ player.name.replace(/^(\w+).*/, "$1") }} Keeper
                </a>
            </div>
            <div v-else class="flex w-0 flex-1">
                <a @click="updateUserRole(player.id, 'player')"
                   class="relative inline-flex w-0 flex-1 items-center justify-center rounded-bl-lg border border-transparent py-4 px-2 text-sm font-semibold text-gray-900"
                >
                    Make {{ player.name.replace(/^(\w+).*/, "$1") }} a Player
                </a>
            </div>
            <div class="-ml-px flex w-0 flex-1">
                <a @click="deleteUser(player.id)"
                   class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                >
                    <TrashIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    Delete User
                </a>
            </div>
        </div>
    </div>
</template>
