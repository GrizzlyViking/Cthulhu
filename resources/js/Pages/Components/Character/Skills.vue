<script setup>
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue';
import SuccessLegend from '@/Pages/Components/SuccessLegend.vue';
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
import { EyeSlashIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid';

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
    availableSkills: Array,
});

const showModal = ref(false);
const query = ref('');

/*
 * Hidden skills stay listed while editing so they can be switched back on;
 * in normal view only the skills marked for the sheet appear.
 */
const visibleSkills = computed(() => {
    const needle = query.value.trim().toLowerCase();

    return (prop.character.skills ?? [])
        .filter((skill) => skill.pivot.show || prop.editable)
        .filter((skill) => !needle || skill.display_name.toLowerCase().includes(needle));
});

const hiddenCount = computed(() =>
    (prop.character.skills ?? []).filter((skill) => !skill.pivot.show).length
);

/** A skill is ready to improve once its experience checks reach value/10. */
const canImprove = (skill) => Math.floor(skill.pivot.value / 10) <= skill.pivot.experience;

const closeEditModal = () => {
    showModal.value = false;
};

const skillForm = useForm({
    display_name: '',
    slug: '',
    value: 0,
    show: true,
});

const updateSkill = () => {
    skillForm.put(route('character.skill.update', {
        character: prop.character.slug,
        skill: skillForm.slug,
    }), { preserveScroll: true, onSuccess: closeModal });
};

const removeSkill = () => {
    router.put(route('character.skill.remove', {
        character: prop.character.slug,
        skill: skillForm.slug,
    }), {}, { preserveScroll: true, onSuccess: closeModal });
};

const skillDescription = ref('');

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
};

const closeModal = () => {
    skillForm.reset();
    showModal.value = false;
};

const resetExperience = (skill) => {
    axios.get(route('experience.reset', {
        character: prop.character.slug,
        skill: skill.slug,
    })).then(() => skill.pivot.experience = 0);
};

const addSkillSlug = ref('');
const addSkillValue = ref(1);

const submitAddSkill = () => {
    if (!addSkillSlug.value) return;
    router.put(
        route('character.skill.attach', {
            character: prop.character.slug,
            skill: addSkillSlug.value,
        }),
        { value: addSkillValue.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                addSkillSlug.value = '';
                addSkillValue.value = 1;
            },
        }
    );
};
</script>

<template>
    <section class="panel p-4 sm:p-5">
        <header class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                <h2 class="text-base font-semibold text-cthulhu-green-900">Skills</h2>
                <SuccessLegend />
            </div>

            <div class="relative w-full sm:w-64">
                <MagnifyingGlassIcon
                    class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-cthulhu-green-500"
                    aria-hidden="true"
                />
                <input
                    v-model="query"
                    type="search"
                    aria-label="Filter skills"
                    placeholder="Filter skills…"
                    class="field ps-9"
                />
            </div>
        </header>

        <p v-if="editable && hiddenCount > 0" class="field-hint mb-3">
            {{ hiddenCount }} hidden {{ hiddenCount === 1 ? 'skill is' : 'skills are' }} greyed out below.
            Open one and tick “Show on sheet” to bring it back.
        </p>

        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="skill in visibleSkills"
                :key="skill.id"
                class="flex items-center justify-between gap-3 rounded-lg px-3 py-2"
                :class="skill.pivot.show
                    ? 'bg-parchment-50 ring-1 ring-parchment-300'
                    : 'bg-parchment-200/40 opacity-60 outline-dashed outline-1 outline-offset-0 outline-parchment-500'"
            >
                <span class="flex min-w-0 items-center gap-1.5">
                    <EyeSlashIcon
                        v-if="!skill.pivot.show"
                        class="size-4 shrink-0 text-cthulhu-green-500"
                        aria-hidden="true"
                    />
                    <span class="truncate text-sm font-semibold text-cthulhu-green-900">{{ skill.display_name }}</span>
                    <span v-if="!skill.pivot.show" class="sr-only">(hidden from the sheet)</span>

                    <button
                        v-if="skill.pivot.experience > 0"
                        type="button"
                        class="tabular grid size-6 shrink-0 place-items-center rounded-full text-xs font-semibold transition"
                        :class="canImprove(skill)
                            ? 'bg-cthulhu-blood-400 text-white ring-1 ring-cthulhu-blood-500 hover:bg-cthulhu-blood-300'
                            : 'bg-cthulhu-yellow-200 text-cthulhu-yellow-700 ring-1 ring-cthulhu-yellow-500 hover:bg-cthulhu-yellow-300'"
                        :title="canImprove(skill)
                            ? `${skill.pivot.experience} experience checks — ready to improve. Tap to clear.`
                            : `${skill.pivot.experience} experience checks. Tap to clear.`"
                        @click="resetExperience(skill)"
                    >
                        {{ skill.pivot.experience }}
                    </button>
                </span>

                <RegularHalfFifth
                    class="!w-[9rem] shrink-0"
                    :skill-value="skill.pivot.value"
                    :interactive="canEdit"
                    :title="canEdit ? `Adjust ${skill.display_name}` : null"
                    @click="openEditModal(skill)"
                />
            </div>
        </div>

        <p v-if="visibleSkills.length === 0" class="py-8 text-center text-sm text-cthulhu-green-500">
            <template v-if="query.trim()">No skills match “{{ query }}”.</template>
            <template v-else-if="hiddenCount > 0">
                Every skill is hidden. Turn on edit mode to bring them back.
            </template>
            <template v-else>No skills on this sheet yet.</template>
        </p>

        <!-- Add skill (edit mode only) -->
        <div v-if="editable && availableSkills && availableSkills.length > 0" class="mt-5 border-t border-parchment-300 pt-4">
            <p class="eyebrow mb-2">Add a skill</p>
            <div class="flex flex-wrap items-center gap-2">
                <select v-model="addSkillSlug" aria-label="Skill to add" class="field w-auto min-w-56 flex-1">
                    <option value="">Select skill…</option>
                    <option v-for="skill in availableSkills" :key="skill.id" :value="skill.slug">
                        {{ skill.display_name }} (base {{ skill.starting_value }})
                    </option>
                </select>
                <input
                    v-model="addSkillValue"
                    type="number"
                    min="0"
                    max="99"
                    inputmode="numeric"
                    aria-label="Starting value"
                    class="field tabular w-20 flex-none"
                />
                <button type="button" class="btn-primary" :disabled="!addSkillSlug" @click="submitAddSkill">
                    Add
                </button>
            </div>
        </div>

        <Modal :show="showModal" max-width="md" @close="closeEditModal">
            <div class="bg-parchment-100 p-6">
                <label for="skill_value" class="field-label">{{ skillForm.display_name }}</label>

                <p v-if="skillDescription" class="field-hint">{{ skillDescription }}</p>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <input
                        id="skill_value"
                        v-model="skillForm.value"
                        type="number"
                        inputmode="numeric"
                        autocomplete="off"
                        data-1p-ignore
                        data-lpignore="true"
                        data-bwignore
                        class="field tabular w-24 flex-none"
                    />

                    <label class="inline-flex items-center gap-2 text-sm text-cthulhu-green-900">
                        <input
                            v-model="skillForm.show"
                            type="checkbox"
                            class="size-4 rounded border-parchment-400 text-cthulhu-green-600 focus:ring-cthulhu-green-600"
                        />
                        Show on sheet
                    </label>

                    <div class="flex-1"></div>

                    <button type="button" class="btn-primary" :disabled="skillForm.processing" @click="updateSkill">
                        {{ skillForm.processing ? 'Saving…' : 'Save' }}
                    </button>

                    <button v-if="canEdit" type="button" class="btn-danger" :disabled="skillForm.processing" @click="removeSkill">
                        Remove
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
