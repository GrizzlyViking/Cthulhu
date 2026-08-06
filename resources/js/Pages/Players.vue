<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useRoles } from "@/Pages/Composables/useRoles.js";

const props = defineProps({ users: Object })

const { isAdmin } = useRoles();
</script>

<template>
    <Head title="Players"/>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Players</h1>
            <!-- Roles, blocking and removal live in the admin section. -->
            <Link v-if="isAdmin" :href="route('admin.users.index')" class="btn-secondary btn-sm">Manage players</Link>
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

                        <div class="flex shrink-0 flex-wrap items-center gap-1.5">
                            <span v-for="role in item.role_names" :key="role" class="chip">{{ role }}</span>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
