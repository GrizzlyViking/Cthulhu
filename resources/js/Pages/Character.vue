<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import Skills from "@/Pages/Components/Character/Skills.vue";
import Weapons from "@/Pages/Components/Character/Weapons.vue";
import Characteristics from "@/Pages/Components/Character/Characteristics.vue";
import Profile from "@/Pages/Components/Character/Profile.vue";
import Vitals from "@/Pages/Components/Character/Vitals.vue";
import {ref} from "vue";
import {Switch} from "@headlessui/vue";

const prop = defineProps({character: Object});
const editable = ref(false);
</script>

<template>
    <Head title="Character"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Character sheet</h2>

                <Switch v-model="editable" :class="[editable ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
                    <span class="sr-only">Use setting</span>
                    <span aria-hidden="true" :class="[editable ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                </Switch>
        </template>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 grid xs:grid-cols-1 sm:grid-cols-2 gap-8">
                        <Profile :character="prop.character" :editable="editable"></Profile>
                    </div>
                </div>
            </div>
        </div>


        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg py-5">
                    <Vitals :character="prop.character"></Vitals>
                </div>
            </div>
        </div>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg">
                    <Characteristics :character="prop.character" :editable="editable"></Characteristics>
                </div>
            </div>
        </div>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg">
                    <Skills :character="prop.character" :editable="editable"></Skills>
                </div>
            </div>
        </div>


        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg py-5">
                    <Weapons :character="prop.character"></Weapons>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
