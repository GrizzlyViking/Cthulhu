<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import ContactsList from "@/Pages/Components/ContactsList.vue";
import Message from "@/Pages/Components/Message.vue";
import {ref} from "vue";

defineProps(['users', 'skills'])

const page = usePage();

let resultOfRoll = ref([]);

const rollFor = (skill) => {
    axios.post(route('skill.roll'), {
        skill_slug: skill,
        users: page.props.auth.listOfRollUsers.map((user) => user.id),
    }).then((response) => {
        resultOfRoll = response.data
    });
}
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
                        ></contacts-list>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4" v-if="page.props.auth.listOfMessageUsers.length > 0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-2 lg:p-6 text-gray-900 lg:w-1/2 mx-auto">
                        <Message></Message>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4" v-if="page.props.auth.listOfRollUsers.length > 0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm sm:rounded-lg">
                    <div class="p-2 lg:p-6 text-gray-900 lg:w-1/2 mx-auto">
                        <button
                            type="button"
                            @click="rollFor('spot-hidden')"
                            class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Spot hidden
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4" v-if="resultOfRoll.length > 0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm sm:rounded-lg p-3">

                    <div class="lg:p-6 text-gray-900 lg:w-3/4 mx-auto bg-cthulhu-green-50 rounded-lg border border-red-600">
                        <ul>
                            <li v-for="result in resultOfRoll" class="m-2">
                                <p>{{ result }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
