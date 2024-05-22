<script setup>

import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const prop = defineProps({character: Object});

const page = usePage()

const isWeaponModalOpen = ref(false);
const closeModal = () => {
    isWeaponModalOpen.value = false;
};
const openModal = () => {
    isWeaponModalOpen.value = true;
};

let addWeapon = (weapon_id) => {
    axios.post(route('character.weapon.add', {
        character: prop.character.slug,
        weapon_id: weapon_id,
    })).then((response) => {
        console.log(response.data)
        reloadWeapons()
    });
    closeModal()
}

let reloadWeapons = () => {
    axios.get(route('character.raw', {
        character: prop.character.slug,
    })).then((response) => {
        prop.character.weapons = response.data.weapons;
    });
}
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Add</h1>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button type="button"
                        @click="openModal"
                        class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Add Weapon
                </button>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                Weapon
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Skill
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Regular
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Damage
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Range
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Attacks
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Bullets in mag
                            </th>
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Malfunction
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        <tr v-for="weapon in prop.character.weapons" :key="weapon.id">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                {{ weapon.name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ weapon.skill }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <regular-half-fifth
                                    v-model="prop.character.strength"></regular-half-fifth>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ weapon.damage }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ weapon.base_range }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ weapon.uses_per_round }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ weapon.bullets_in_mag }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ weapon.malfunction }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <modal-popup :is-open="isWeaponModalOpen"
                 @modal-close="closeModal"
                 @response1=""
                 @response2="closeModal"
    >
        <template #title>Add weapons</template>
        <template #default>
            <ul role="list" class="divide-y divide-gray-100">
                <li v-for="w in page.props.auth.equipment" :key="w.id" class="flex gap-x-4 py-5" @click="addWeapon(w.id)">
                    <div class="min-w-0 justify-between">
                        <p class="inline-flex text-sm font-semibold leading-6 text-gray-900">{{ w.name }}</p>
                        <p class="inline-flex mt-1 ml-5 truncate text-xs leading-5 text-gray-500">{{ w.damage }} / {{ w.base_range }}</p>
                    </div>
                </li>
            </ul>
        </template>
        <template #response1></template>
        <template #response2>Finished</template>
    </modal-popup>
</template>

<style scoped>

</style>
