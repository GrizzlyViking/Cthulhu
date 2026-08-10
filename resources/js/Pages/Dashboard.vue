<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ContactsList from '@/Pages/Components/ContactsList.vue';
import { useRoles } from '@/Pages/Composables/useRoles.js';

defineProps(['users']);

const { isKeeper } = useRoles();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Dashboard</h1>
            <!-- Rolling in secret lives on the Keeper's screen, next to the party it is rolled against. -->
            <Link v-if="isKeeper" :href="route('keeper.index')" class="btn-secondary btn-sm">Keeper screen</Link>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-6">
                <contacts-list :users="users"></contacts-list>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
