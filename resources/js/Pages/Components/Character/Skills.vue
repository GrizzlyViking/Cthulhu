<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import axios from "axios";

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
});

const showModal = ref(false);

const closeEditModal = () => {
    showModal.value = false;
};

const skillForm = useForm({
    display_name: '',
    slug: '',
    value: 0,
    show: true,
})

const updateSkill = () => {
    skillForm.put(route('character.skill.update', {
        character: prop.character.slug,
        skill: skillForm.slug,
    }), { preserveScroll: true, onSuccess: closeModal })
}

const skillDescription = ref('')
const openEditModal = (skill) => {
    if (!prop.canEdit) {
        return;
    }
    skillForm.display_name = skill.display_name;
    skillForm.value = skill.pivot.value;
    skillForm.slug = skill.slug;
    skillForm.show = skill.pivot.show;
    skillDescription.value = skill.description;
    showModal.value = true;
}

const closeModal = () => {
    skillForm.reset();
    showModal.value = false;
}

const resetExperience = (skill) => {
    axios.get(route('experience.reset', {
        character: prop.character.slug,
        skill: skill.slug
    })).then(() => skill.pivot.experience = 0);
}
</script>

<template>
    <div class="shadow-sm rounded-lg">
        <div class="p-6 columns-1 md:columns-2 lg:columns-3 gap-x-3">
            <template v-for="skill in prop.character.skills" :key="skill.id">
                <div
                    v-if="skill.pivot.show"
                    class="mb-3 break-inside-avoid grid grid-cols-2 justify-between overflow-clip"
                    style="break-inside: avoid"
                    :class="{ 'opacity-50': !skill.pivot.show }"
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
            </template>
        </div>
    </div>

    <Modal :show="showModal" @close="closeEditModal" max-width="md">
        <div class="bg-cthulhu-green-200 p-6 border-cthulhu-green-100 align-bottom">
            <div class="flex flex-col h-full gap-1">
                <label
                    for="starting_value"
                    class="block text-md font-medium leading-6 text-gray-900"
                >
                    {{ skillForm.display_name }}
                </label>

                <div class="flex-grow"></div>

                <div class="flex items-end gap-2">
                    <input
                        type="number"
                        v-model="skillForm.value"
                        inputmode="numeric"
                        autocomplete="off"
                        data-1p-ignore
                        data-lpignore="true"
                        data-bwignore
                        class="block w-full rounded-md border-0 py-1.5
                       text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                       placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />

                    <div class="flex items-center h-9">
                        <input
                            id="show_skill"
                            type="checkbox"
                            v-model="skillForm.show"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                    </div>

                    <button
                        type="button"
                        @click="updateSkill"
                        :disabled="skillForm.processing"
                        class="rounded-md bg-cthulhu-green-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:opacity-50"
                    >
                        {{ skillForm.processing ? 'Saving...' : 'Save' }}
                    </button>
                </div>

                <div v-if="skillDescription" class="mt-4 text-sm text-gray-700 italic">
                    {{ skillDescription }}
                </div>
            </div>
        </div>
    </Modal>

</template>
