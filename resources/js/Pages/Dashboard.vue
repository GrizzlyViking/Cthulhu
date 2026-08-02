<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import ContactsList from "@/Pages/Components/ContactsList.vue";
import Message from "@/Pages/Components/Message.vue";
import {ref} from "vue";

defineProps(['users', 'skills'])

const page = usePage();

let resultOfRoll = ref([]);
let showResultOfRoll = ref(false);

const rollFor = (skill) => {
    axios.post(route('skill.roll'), {
        skill_slug: skill,
        users: page.props.auth.listOfRollUsers.map((user) => user.id),
    }).then((response) => {
        resultOfRoll = response.data;
        showResultOfRoll.value = true;
    });
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Dashboard</h1>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-6">
                <contacts-list :users="users"></contacts-list>
            </section>

            <section v-if="page.props.auth.listOfMessageUsers.length > 0" class="panel p-4 sm:p-6">
                <h2 class="mb-4 text-base font-semibold text-cthulhu-green-900">Send a message</h2>
                <Message></Message>
            </section>

            <section v-if="page.props.auth.listOfRollUsers.length > 0" class="panel p-4 sm:p-6">
                <h2 class="mb-1 text-base font-semibold text-cthulhu-green-900">Secret roll</h2>
                <p class="field-hint mb-4">Rolls against the selected players without telling them.</p>
                <button type="button" class="btn-primary" @click="rollFor('spot-hidden')">
                    Roll Spot Hidden
                </button>
            </section>

            <section v-if="showResultOfRoll" class="panel p-4 sm:p-6">
                <h2 class="mb-3 text-base font-semibold text-cthulhu-green-900">Result</h2>
                <ul class="flex flex-col gap-2">
                    <li
                        v-for="(result, index) in resultOfRoll"
                        :key="index"
                        class="rounded-lg bg-parchment-50 px-3 py-2 text-sm text-cthulhu-green-900 ring-1 ring-cthulhu-blood-400/40"
                    >
                        {{ result }}
                    </li>
                </ul>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
