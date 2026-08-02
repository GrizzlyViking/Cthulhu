<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import SimpleList from "@/Pages/Components/SimpleList.vue";

const props = defineProps({ messages: Object })
</script>

<template>
    <Head title="Messages"/>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Messages</h1>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-6">
                <simple-list :list="messages"/>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
