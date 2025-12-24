<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {debounce} from "lodash";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {ref} from "vue";
import {useForm, router} from "@inertiajs/vue3";

const prop = defineProps({character: Object, editable: Boolean});

let isEditSkillModalOpen = ref(false);

const closeEditModal = () => {
    isEditSkillModalOpen.false = false;
};

let newSkill = useForm({
    character_id: prop.character.id,
    display_name: '',
    starting_value: 0,
    new_value: 0,
    slug: ''
})

const updateSkill = debounce((skill) => {
    router.put(route('character.skill.update', {
        character: prop.character.slug,
        skill: skill.slug,
    }), {
        value: Number(skill.new_value)
    }, {
        preserveScroll: true,
        onSuccess: () => isEditSkillModalOpen.false = false
    });
}, 600);

let deleteSkill = (skill) => {
    axios.put(route('character.skill.remove', {
        character: prop.character.slug,
        skill: skill.slug,
    }), {
        preserveScroll: true,
    }).then (() => {
        isEditSkillModalOpen.value = false;
        prop.character.skills = prop.character.skills.filter(s => s.slug !== skill.slug);
    })
}

let openEditModal = (skill) => {
    newSkill.character_id = prop.character.id;
    newSkill.display_name = skill.display_name;
    newSkill.new_value = skill.pivot.value;
    newSkill.slug = skill.slug;
    isEditSkillModalOpen.value = true;
}

let cancelEditSkill = () => {
    isEditSkillModalOpen.value = false;
    newSkill.reset();
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
        <div class="p-6 m-3 columns-1 md:columns-2 lg:columns-3 gap-x-3">
            <div
                v-for="skill in prop.character.skills"
                :key="skill.id"
                class="mb-3 break-inside-avoid grid grid-cols-2 justify-between overflow-clip"
                style="break-inside: avoid"
            >
            <div class="font-bold p-2 align-middle text-right">
                <span>{{ skill.display_name }}</span>
                    <div v-if="skill.pivot.experience > 0" class="ml-1 inline-block" @click="resetExperience(skill)">
                        <div
                            class="align-middle text-center rounded-full border w-6 h-6"
                            :class="{
                                'border-gray-200': (Math.floor(skill.pivot.value/10) > skill.pivot.experience),
                                'border-red-800 bg-red-800 colour text-color-white': (Math.floor(skill.pivot.value/10) <= skill.pivot.experience)
                              }"
                        > {{ skill.pivot.experience }}</div>
                    </div>
                </div>

                <regular-half-fifth @click="openEditModal(skill)" :skill-value="skill.pivot.value"></regular-half-fifth>
            </div>
        </div>
    </div>

    <modal-popup :is-open="isEditSkillModalOpen"
                 @modal-close="closeEditModal"
                 @response1="closeEditModal"
                 @response2="() => updateSkill(newSkill)"
    >
        <template #title>Edit skill</template>
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
                        <input type="number" v-model="newSkill.new_value"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                    </div>
                </div>
            </form>
        </template>
        <template #buttons>
            <div class="mt-4 flex justify-between gap-2">
                <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="cancelEditSkill"
                >Cancel
                </button>
                <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="updateSkill(newSkill)"
                >
                    Update
                </button>
                <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="deleteSkill(newSkill)"
                >
                    Delete
                </button>
            </div>
        </template>
    </modal-popup>

</template>
