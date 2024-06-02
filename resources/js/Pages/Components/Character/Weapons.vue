<script setup>

import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid'

const prop = defineProps({character: Object, editable: Boolean});

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
        refreshWeapons()
    });
    closeModal()
}

let refreshWeapons = () => {
    axios.get(route('character.raw', {
        character: prop.character.slug,
    })).then((response) => {
        prop.character.weapons = response.data.weapons;
    });
}

const reload = (weapon) => {
    axios.post(route('reload.weapon'), {
        pivot_id: weapon.pivot.id,
        ammo: weapon.bullets_in_mag,
    }).then (() => {
        weapon.pivot.ammo = weapon.bullets_in_mag
    })
}

const fireTheWeapon = (weapon) => {
    axios.post(route('fire.weapon'), {
        pivot_id: weapon.pivot.id,
    }).then (() => {
        weapon.pivot.ammo = weapon.pivot.ammo-1
    })
}

let weaponSkill = computed(() => {
   return (slug) => {
       let answer = prop.character.skills.filter(skill => skill.slug === slug)
       let rawObject = JSON.parse(JSON.stringify(answer));
       if(rawObject[0] !== undefined) {
           return rawObject[0].pivot;
       }
       return {}
   }
});
</script>

<template>
    <ul role="list" class="ml-2 divide-y divide-gray-100">
        <li v-for="weapon in prop.character.weapons" :key="weapon.slug" class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900 gap-2">
                        <span class="hover:underline">{{ weapon.name }}</span>
                        <span v-if="!isNaN(weapon.bullets_in_mag)"
                              @click="fireTheWeapon(weapon)"
                              class="pl-4 hover:underline"
                        >Ammo: {{ weapon.pivot.ammo }}</span>
                    </p>
                    <p class="gap-3 mt-1 flex text-xs leading-5 text-gray-500">
                        <span>{{ weaponSkill(weapon.skill).value }} / {{ Math.ceil(weaponSkill(weapon.skill).value / 2) }} / {{ Math.ceil(weaponSkill(weapon.skill).value / 5) }}</span>
                        <span class="truncate hover:underline">{{ weapon.damage }}</span>
                        <span class="truncate hover:underline">{{ weapon.base_range }}</span>
                    </p>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-x-6">
                <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm leading-6 text-gray-900">{{ weapon.base_range }}</p>
                    <div class="mt-1 flex items-center gap-x-1.5">
                        <p class="text-xs leading-5 text-gray-500">{{ weapon.uses_per_round }}</p>
                    </div>
                </div>
                <Menu as="div" class="relative flex-none">
                    <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
                        <span class="sr-only">Open options</span>
                        <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                    </MenuButton>
                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                        <MenuItems class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                            <MenuItem v-slot="{ active }" v-if="!isNaN(weapon.bullets_in_mag)">
                                <a @click="reload(weapon)" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                                >Reload<span class="sr-only"></span></a
                                >
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                                <a href="#" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                                >Message<span class="sr-only">, {{ weapon.name }}</span></a
                                >
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
        </li>
    </ul>
    <div class="flex justify-end p-6 w-full">
        <button type="button"
                @click="openModal"
                class="block rounded-md bg-cthulhu-green-400 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Add Weapon
        </button>
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
