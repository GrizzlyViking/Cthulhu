<script setup>
import { Head, router } from "@inertiajs/vue3";
import { TrashIcon } from "@heroicons/vue/20/solid/index.js";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({ users: Object })

const roles = [
    { name: 'player' },
    { name: 'Keeper of Arcane Lore' },
]

const updateUserRole = (user_id, event) => {
    router.put(
        route('users.role', { user: user_id }),
        { role: event.target.value }
    )
}

const deleteUser = (user_id) => {
    if (confirm("Are you sure you want to delete this user?")) {
        router.delete(route('users.destroy', { user: user_id }))
    }
}
</script>

<template>
    <Head title="Player management"/>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Player management</h1>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-6">
                <ul role="list" class="flex flex-col gap-2">
                    <li
                        v-for="item in props.users"
                        :key="item.id"
                        class="flex flex-wrap items-center justify-between gap-3 rounded-lg bg-parchment-50 p-3 ring-1 ring-parchment-300"
                    >
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold text-cthulhu-green-900">{{ item.name }}</p>
                            <p class="mt-1 flex items-center gap-1.5 text-xs text-cthulhu-green-500">
                                <span
                                    class="size-2 rounded-full"
                                    :class="item.isOnline ? 'bg-cthulhu-green-350' : 'bg-cthulhu-green-500/40'"
                                ></span>
                                {{ item.isOnline ? 'Online' : 'Offline' }}
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <label :for="`role-${item.id}`" class="sr-only">Role</label>
                            <select
                                :id="`role-${item.id}`"
                                v-model="item.role"
                                class="field w-auto"
                                @change="updateUserRole(item.id, $event)"
                            >
                                <option v-for="role in roles" :key="role.name" :value="role.name">
                                    {{ role.name }}
                                </option>
                            </select>

                            <button type="button" class="btn-danger btn-sm" @click="deleteUser(item.id)">
                                <TrashIcon class="size-4" aria-hidden="true"/>
                                Delete
                            </button>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
