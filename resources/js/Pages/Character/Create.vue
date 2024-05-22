<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>

<template>
    <Head title="Character"/>

    <AuthenticatedLayout>
        <template #header>
            Character Create
        </template>

        Hello world
    </AuthenticatedLayout>


</template>

<style scoped>

</style>
