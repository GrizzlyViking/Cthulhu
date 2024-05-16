<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {debounce} from "lodash";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";

const prop = defineProps({character: Object});

let adjustHitPoints = debounce((event) => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'hit_points',
        value: event.target.value
    }));
    if (Math.floor(prop.character.hit_points / 2) > Number(event.target.value)) {
        openHitPointsModal();
    }
    prop.character.hit_points = event.target.value;
}, 600)

let unconscious = (setValue) => {
    prop.character.unconscious = setValue;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'unconscious',
        value: prop.character.unconscious
    }));
    closeModal()
}

let adjustSkill = debounce((skill, event) => {
    axios.put(route('character.skill.update', {
        character: prop.character.slug,
        skill: skill.slug,
        value: event.target.value
    }));
}, 600);

let adjustLuck = debounce(() => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'luck',
        value: prop.character.luck
    }));
}, 600);

let adjustMagicPoints = debounce(() => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'magic_points',
        value: prop.character.magic_points
    }));
}, 600);

let adjustSanity = debounce((event) => {
    if ((Number(prop.character.sanity) - 5) > Number(event.target.value)) {
        openSanityModal();
    }
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'sanity',
        value: event.target.value
    }));
    prop.character.sanity = event.target.value;
}, 600)

let temporaryInsanity = (setValue) => {
    prop.character.temporary_insanity = setValue;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'temporary_insanity',
        value: setValue
    }));
    console.log('temporary_insanity');
    closeModal();
}

let submit = () => {
    useForm.put('/character/' + prop.character.slug)
}

const isHitPointsModalOpen = ref(false);
const isSanityModalOpen = ref(false);

const openHitPointsModal = () => {
    isHitPointsModalOpen.value = true;
};
const openSanityModal = () => {
    isSanityModalOpen.value = true;
};

const closeModal = () => {
    isHitPointsModalOpen.value = false;
    isSanityModalOpen.value = false;
};

</script>

<template>
    <Head title="Character"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Character sheet</h2>
        </template>

        <div class="py-12 m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 grid grid-cols-2 gap-8">
                        <div class="justify-between">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Name:</span>
                                <span>{{ prop.character.name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Player:</span> <span>{{ prop.character.player.name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Occupation:</span>
                                <span class="text-right">{{ prop.character.occupation }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Age:</span> <span>{{ prop.character.age }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Gender:</span> <span>{{ prop.character.gender }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Residence:</span>
                                <span>{{ prop.character.residence }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Birthplace:</span>
                                <span>{{ prop.character.birthplace }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between h-7">
                                <span class="text-gray-400">Damage bonus:</span>
                                <span>{{ prop.character.damage_bonus }}</span>
                            </div>
                            <div class="flex justify-between h-7">
                                <span class="text-gray-400">Build:</span>
                                <span>{{ prop.character.build }}</span>
                            </div>
                            <div class="flex justify-between h-7">
                                <span class="text-gray-400 my-auto">Dodge:</span>
                                <regular-half-fifth v-model="prop.character.dodge"></regular-half-fifth>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="py-12 m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg py-5">
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-8 text-center lg:grid-cols-4">
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-gray-600">
                                Hit Points
                                <div class="text-gray-400 text-xs">Max hit points:
                                    {{ Math.floor((Number(character.constitution) + Number(character.size)) / 5) }}
                                </div>
                            </dt>
                            <input :value="prop.character.hit_points" @input="adjustHitPoints($event)" type="text"
                                   class="transition border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
                            <div
                                @click="unconscious(!prop.character.unconscious)"
                                class="h-8 order-last border border-gray-200 rounded-lg px-2 p-1"
                                :class="{
                                    'bg-red-300': prop.character.unconscious
                                }"
                            >
                                Unconscious
                            </div>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-gray-600">
                                Sanity
                                <div class="text-gray-400 text-xs">Starting sanity: {{ prop.character.power }}</div>
                            </dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                                <input :value="prop.character.sanity"
                                       @input="adjustSanity($event)"
                                       class="border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight"
                                       type="text">
                            </dd>
                            <div
                                @click="temporaryInsanity(!prop.character.temporary_insanity)"
                                class="h-8 order-last border border-gray-200 rounded-lg px-2 p-1"
                                :class="{
                                    'bg-red-300': prop.character.temporary_insanity
                                }"
                            >
                                Temporary Insanity
                            </div>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-gray-600">Luck</dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                                <input v-model="prop.character.luck" type="text" @input="adjustLuck"
                                       class="border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
                            </dd>
                        </div>
                        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                            <dt class="text-base leading-7 text-gray-600">
                                Magic points
                                <div class="text-gray-400 text-xs">Max magic points:
                                    {{ Math.floor(Number(prop.character.power) / 5) }}
                                </div>
                            </dt>
                            <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                                <input v-model="prop.character.magic_points" type="text" @input="adjustMagicPoints"
                                       class="border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>


        <div class="m-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm rounded-lg">

                    <div class="p-3 m-3 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-3">
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Strength</div>
                            <regular-half-fifth v-model="prop.character.strength"></regular-half-fifth>
                        </div>
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Dexterity</div>
                            <regular-half-fifth v-model="prop.character.dexterity"></regular-half-fifth>
                        </div>
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Intelligence</div>
                            <regular-half-fifth v-model="prop.character.intelligence"></regular-half-fifth>
                        </div>
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Constitution</div>
                            <regular-half-fifth v-model="prop.character.constitution"></regular-half-fifth>
                        </div>
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Appearance</div>
                            <regular-half-fifth v-model="prop.character.appearance"></regular-half-fifth>
                        </div>
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Power</div>
                            <regular-half-fifth v-model="prop.character.power"></regular-half-fifth>
                        </div>

                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Size</div>
                            <regular-half-fifth v-model="prop.character.size"></regular-half-fifth>
                        </div>
                        <div class="grid grid-cols-2 justify-between">
                            <div class="font-bold p-2 align-middle text-right">Education</div>
                            <regular-half-fifth v-model="prop.character.education"></regular-half-fifth>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6 m-3 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-3">
                        <div v-for="skill in prop.character.skills" :key="skill.id"
                             class="grid grid-cols-2 justify-between overflow-clip">

                            <div
                                class="font-bold p-2 align-middle text-right"
                            ><span @click="skill.pivot.experience = skill.pivot.experience+1">{{
                                    skill.display_name
                                }}</span>
                                <div v-if="skill.pivot.experience > 0" class="ml-1 inline-block"
                                     @click="skill.pivot.experience = skill.pivot.experience-1">
                                    <div
                                        class="align-middle text-center rounded-full border w-6 h-6"
                                        :class="{
                                            'border-gray-200': (Math.floor(skill.pivot.value/10) > skill.pivot.experience),
                                            'border-red-800 bg-red-800 colour text-color-white': (Math.floor(skill.pivot.value/10) <= skill.pivot.experience)
                                        }"
                                    >
                                        {{ skill.pivot.experience }}

                                    </div>
                                </div>
                            </div>

                            <regular-half-fifth v-model="skill.pivot.value"
                                                @input="adjustSkill(skill, $event)"></regular-half-fifth>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-12 m-3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow-sm rounded-lg py-5">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
                                </div>
                                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                    <button type="button"
                                            class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Add user
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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <modal-popup :is-open="isHitPointsModalOpen"
                 @modal-close="closeModal"
                 @response1="closeModal"
                 @response2="unconscious"
    >
        <template #title>Lots of damage!?!</template>
        <template #default>
            You have take more than half of you present hit points. You therefore need to pass a test against your
            constitution ({{ prop.character.constitution }}). If you fail you will fall unconscious.
        </template>
        <template #response1>I passed my constitution test</template>
        <template #response2>I failed my constitution test</template>
    </modal-popup>

    <modal-popup :is-open="isSanityModalOpen"
                 @modal-close="closeModal"
                 @response1="closeModal"
                 @response2="temporaryInsanity(true)"
    >
        <template #title>Insane in the membrane</template>
        <template #default>
            If an investigator loses 5 or more Sanity points as the consequence of a single Sanity roll, they have
            suffered major emotional trauma. The player must roll 1D100. If the result is equal to or less than their
            Intelligence ({{ prop.character.intelligence }}), the investigator fully understands and comprehends what
            has been seen and goes temporarily insane (for 1D10 hours). If they fail the roll, their mind is closed to
            the horror and they remain sane (for now).
        </template>
        <template #response1>I passed</template>
        <template #response2>I'm mad as a box of frogs.</template>
    </modal-popup>
</template>
