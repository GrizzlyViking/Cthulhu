<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import ContactsList from "@/Pages/Components/ContactsList.vue";
import Message from "@/Pages/Components/Message.vue";
import {ref} from "vue";

defineProps(['users'])

let ListOfUsers = ref([]);
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <contacts-list
                            :users="users"
                            :selected-for-message="ListOfUsers"
                        ></contacts-list>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 w-1/2 mx-auto">
                        <Message :selected-for-message="ListOfUsers"></Message>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
