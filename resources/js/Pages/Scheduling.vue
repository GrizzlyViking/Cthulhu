<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Calendar from "@/Pages/Components/Calendar.vue";

const props = defineProps({ calendar: Object })
</script>

<template>
    <Head title="Calendar"/>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Calendar</h1>
        </template>

        <div class="page">
            <section class="panel overflow-hidden p-4 sm:p-6">
                <calendar :calendar="props.calendar" />
            </section>
        </div>
    </AuthenticatedLayout>
</template>
