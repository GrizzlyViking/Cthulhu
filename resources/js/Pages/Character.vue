<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import Skills from "@/Pages/Components/Character/Skills.vue";
import Weapons from "@/Pages/Components/Character/Weapons.vue";
import Characteristics from "@/Pages/Components/Character/Characteristics.vue";
import Profile from "@/Pages/Components/Character/Profile.vue";
import Vitals from "@/Pages/Components/Character/Vitals.vue";
import {ref} from "vue";
import {Switch} from "@headlessui/vue";

const prop = defineProps({character: Object});
const editable = ref(false);

const deleteCharacter = (character) => {
    if (confirm("Are you sure you want to delete?")) {
        axios.delete(route('character.delete', {
            character: prop.character.slug,
        }))
    }
}
</script>

<template>
    <Head :title="prop.character.name"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-cthulhu-green-800 leading-tight"><span class="limelight-regular">{{ prop.character.name }}</span> - ({{ prop.character.player.name }})</h2>

            <Switch v-model="editable"
                    :class="[editable ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
                <span class="sr-only">Use setting</span>
                <span aria-hidden="true"
                      :class="[editable ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-cthulhu-green-200 shadow ring-0 transition duration-200 ease-in-out']"/>
            </Switch>
        </template>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg">
                    <div class="p-6 grid xs:grid-cols-1 sm:grid-cols-2 gap-8">
                        <Profile :character="prop.character" :editable="editable"></Profile>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg py-5">
                    <Vitals :character="prop.character"></Vitals>
                </div>
            </div>
        </div>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg">
                    <Characteristics :character="prop.character" :editable="editable"></Characteristics>
                </div>
            </div>
        </div>

        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg">
                    <Skills :character="prop.character" :editable="editable"></Skills>
                </div>
            </div>
        </div>


        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg py-5">
                    <Weapons :character="prop.character"></Weapons>
                </div>
            </div>
        </div>

        <div class="m-3" v-if="editable">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-cthulhu-green-200 shadow-sm rounded-lg py-5">
                    <div class="p-6 grid xs:grid-cols-1 sm:grid-cols-2 gap-8">
                    <button type="button" @click="deleteCharacter(prop.character)"
                            class="rounded-md bg-cthulhu-green-200 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-red-50">
                        Delete {{ prop.character.name }}
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.limelight-regular {
    font-family: "Limelight", sans-serif;
    font-weight: 400;
    font-style: normal;
}
</style>
