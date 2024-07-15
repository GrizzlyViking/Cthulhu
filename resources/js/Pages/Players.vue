<script setup>
import {Head, router} from "@inertiajs/vue3";
import {XCircleIcon} from "@heroicons/vue/20/solid/index.js";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({ users: Object })
const roles = [{
    name: 'player',
},{
    name: 'Keeper of Arcane Lore'
}]

const updateUserRole = (user_id, event) => {
    router.put(
        route('users.role', {user: user_id}),
        { role: event.target.value }
    )
}

const deleteUser = (user_id) => {
    router.delete(route('users.destroy', {user: user_id}))
}
</script>

<template>
    <Head title="Player management"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Player management</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <ul role="list" class="divide-y divide-gray-100">
                            <li v-for="item in props.users" :key="item.id" class="flex justify-between gap-x-6 py-5">
                                <div class="flex min-w-0 gap-x-4">
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ item.name }}</p>
                                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ item.content }}</p>
                                    </div>
                                </div>
                                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                    <div class="mt-1 flex items-center gap-x-1.5">
                                        <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                                            <div v-if="item.isOnline" class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                            <div v-else class="h-1.5 w-1.5 rounded-full bg-red-500" />
                                        </div>
                                        <p v-if="item.isOnline" class="text-xs leading-5 text-gray-500">Online</p>
                                        <p v-else class="text-xs leading-5 text-gray-500">Offline</p>
                                    </div>
                                    <button
                                        @click="deleteUser(item.id)"
                                        type="button"
                                        class="inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        <XCircleIcon class="-ml-0.5 h-5 w-5" aria-hidden="true"/>
                                        Toggle Role
                                    </button>
                                    <select v-model="item.role" @change="updateUserRole(item.id, $event)"
                                            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option v-for="role in roles" :key="role.name" :value="role.name"
                                                :selected="role.name === item.role">{{ role.name }}
                                        </option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
