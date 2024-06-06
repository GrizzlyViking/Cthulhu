<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {debounce} from "lodash";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {ref} from "vue";
import {useForm, router} from "@inertiajs/vue3";

const prop = defineProps({character: Object, editable: Boolean});

const isAddSkillModalOpen = ref(false);
const closeModal = () => {
    isAddSkillModalOpen.value = false;
};
const openModal = () => {
    isAddSkillModalOpen.value = true;
};

let newSkill = useForm({
    character_id: prop.character.id,
    display_name: '',
    starting_value: 0
})

const addSkill = () => {
    newSkill.post(route('skill.store'), {
        preserveScroll: true,
        onSuccess: () => {
            reloadSkills()
            closeModal()
        },
    })
}

let reloadSkills = () => {
    axios.get(route('character.raw', {
        character: prop.character.slug,
    })).then((response) => prop.character.skills = response.data.skills)
}

let adjustSkill = debounce((skill, event) => {
    axios.put(route('character.skill.update', {
        character: prop.character.slug,
        skill: skill.slug
    }), {
        value: Number(event.target.value)
    });
}, 600);

let incrementExperience = (skill) => {
    axios.get(route('experience.increment', {
        character: prop.character.slug,
        skill: skill.slug
    })).then(() => skill.pivot.experience = skill.pivot.experience + 1);
}

let resetExperience = (skill) => {
    axios.get(route('experience.reset', {
        character: prop.character.slug,
        skill: skill.slug
    })).then(() => skill.pivot.experience = 0);
}
</script>

<template>
    <div class="shadow-sm rounded-lg">
        <div class="p-6 m-3 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-3">
            <div v-for="skill in prop.character.skills" :key="skill.id"
                 class="grid grid-cols-2 justify-between overflow-clip">

                <div
                    class="font-bold p-2 align-middle text-right"
                ><span @click="incrementExperience(skill)">{{
                        skill.display_name
                    }}</span>
                    <div v-if="skill.pivot.experience > 0" class="ml-1 inline-block"
                         @click="resetExperience(skill)">
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
                                    @input="adjustSkill(skill, $event)"
                                    :editable="editable"
                ></regular-half-fifth>
            </div>
        </div>
        <div v-if="editable" class="flex justify-end p-6 w-full">
            <button type="button"
                    @click="openModal"
                    class="block rounded-md bg-cthulhu-green-400 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Add Skill
            </button>
        </div>
    </div>

    <modal-popup :is-open="isAddSkillModalOpen"
                 @modal-close="closeModal"
                 @response1="closeModal"
                 @response2="addSkill"
    >
        <template #title>Add skill</template>
        <template #default>
            <form>
                <div>
                    <label for="display_name" class="block text-sm font-medium leading-6 text-gray-900">Display
                        name</label>
                    <div class="mt-2">
                        <input type="text" v-model="newSkill.display_name"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                    </div>
                </div>
                <div>
                    <label for="starting_value" class="block text-sm font-medium leading-6 text-gray-900">Starting
                        value</label>
                    <div class="mt-2">
                        <input type="number" v-model="newSkill.starting_value"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                    </div>
                </div>
            </form>
        </template>
        <template #response1>Cancel</template>
        <template #response2>Save</template>
    </modal-popup>
</template>

<style scoped>

</style>
