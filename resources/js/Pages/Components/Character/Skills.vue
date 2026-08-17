<script setup>
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue';
import SuccessLegend from '@/Pages/Components/SuccessLegend.vue';
import TallyMarks from '@/Pages/Components/TallyMarks.vue';
import { computed, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import Modal from '@/Components/Modal.vue';
import EraChips from '@/Components/EraChips.vue';
import axios from 'axios';
import { belongsToEra, eraSummary } from '@/Pages/Composables/useEra.js';
import { EyeSlashIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid';

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
    availableSkills: Array,
    /** Slugs from cthulhu.sheet.always_relevant_skills — shown however untouched. */
    alwaysRelevantSkills: { type: Array, default: () => [] },
    /** The group's era, and every era the server knows about. */
    era: { type: String, default: null },
    eras: { type: Array, default: () => [] },
});

const showModal = ref(false);
const query = ref('');

const RELEVANT_ONLY_KEY = 'cthulhu.skills.relevant-only';

/*
 * Inertia remounts this page after every save, so the choice has to outlive the
 * component or it would snap back on halfway through a session.
 */
const readRelevantOnly = () => {
    try {
        return window.localStorage.getItem(RELEVANT_ONLY_KEY) !== 'false';
    } catch {
        return true;
    }
};

const relevantOnly = ref(readRelevantOnly());

watch(relevantOnly, (value) => {
    try {
        window.localStorage.setItem(RELEVANT_ONLY_KEY, value ? 'true' : 'false');
    } catch {
        // A browser refusing storage is not worth breaking the sheet over.
    }
});

const alwaysRelevant = computed(() => new Set(prop.alwaysRelevantSkills ?? []));

/**
 * A skill is relevant once the player has put points into it — its value is
 * above the book's starting value — or if it is one of the handful of slugs
 * that come up too often to hide.
 */
const isRelevant = (skill) =>
    alwaysRelevant.value.has(skill.slug) || skill.pivot.value > (skill.starting_value ?? 0);

/**
 * Skills belonging to another era. Every sheet starts with the whole canonical
 * list on it, so a 1920s investigator arrives carrying Fighting (Chainsaw); it
 * is not worth a box until somebody has put points into it.
 */
const inEra = (skill) => belongsToEra(skill, prop.era);

const outOfEraLabel = (skill) => eraSummary(skill, prop.eras);

/** The skills on this sheet at all: hidden ones stay listed while editing. */
const listedSkills = computed(() =>
    (prop.character.skills ?? []).filter((skill) => skill.pivot.show || prop.editable)
);

const visibleSkills = computed(() => {
    const needle = query.value.trim().toLowerCase();

    return listedSkills.value
        .filter((skill) => !needle || skill.display_name.toLowerCase().includes(needle))
        // Typing a name is an explicit hunt for one skill, so it looks past the filter.
        .filter((skill) => !relevantOnly.value || needle !== '' || isRelevant(skill))
        // Out of era is only hidden while it is also untouched — a Keeper who
        // has handed one out has said it belongs, and searching reaches it.
        .filter((skill) => inEra(skill) || needle !== '' || isRelevant(skill));
});

/** Skills the era is holding back right now. */
const outOfEraCount = computed(() =>
    query.value.trim()
        ? 0
        : listedSkills.value.filter((skill) => !inEra(skill) && !isRelevant(skill)).length
);

const hiddenCount = computed(() =>
    (prop.character.skills ?? []).filter((skill) => !skill.pivot.show).length
);

/*
 * Skills the relevance filter is holding back right now. The era holds its own
 * back whatever the switch says, so the two counts partition rather than
 * overlap and each hint stays true.
 */
const unimprovedCount = computed(() =>
    relevantOnly.value && !query.value.trim()
        ? listedSkills.value.filter((skill) => !isRelevant(skill) && inEra(skill)).length
        : 0
);

/**
 * House rule: a successful roll earns the skill a mark, and once the marks
 * reach a tenth of its value the investigator may roll to improve it —
 * Language (Gaelic) at 21 is ready on the second mark. Skills too low to give
 * a tenth still need one mark, or they would arrive already improvable.
 */
const improveAt = (value) => Math.max(1, Math.floor(value / 10));

const canImprove = (skill) => skill.pivot.experience >= improveAt(skill.pivot.value);

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

/** The skill the modal is open on, kept so its checks can be counted there. */
const editingSkill = ref(null);

const openEditModal = (skill) => {
    if (!prop.canEdit) {
        return;
    }
    skillForm.display_name = skill.display_name;
    skillForm.value = skill.pivot.value;
    skillForm.slug = skill.slug;
    skillForm.show = skill.pivot.show;
    skillDescription.value = skill.description;
    editingSkill.value = skill;
    showModal.value = true;
};

const closeModal = () => {
    skillForm.reset();
    showModal.value = false;
};

const experience = computed(() => editingSkill.value?.pivot.experience ?? 0);

/* Read off the field rather than the pivot, so retyping the value moves the bar. */
const experienceThreshold = computed(() => improveAt(Number(skillForm.value) || 0));

const readyToImprove = computed(() => experience.value >= experienceThreshold.value);

const changingExperience = ref(false);

/**
 * Checks are counted on the server, which clamps them and hands back what it
 * stored — so the tally in the skill's box follows without a page reload.
 */
const changeExperience = (action) => {
    const skill = editingSkill.value;

    if (!skill || changingExperience.value) {
        return;
    }

    changingExperience.value = true;

    axios.post(route(action, {
        character: prop.character.slug,
        skill: skill.slug,
    }))
        .then(({ data }) => skill.pivot.experience = data.experience)
        .finally(() => changingExperience.value = false);
};

const addSkillSlug = ref('');
const addSkillValue = ref(1);

/*
 * The skills that can still be added, this era's first. Nothing is withheld —
 * a Keeper may well want something out of period — but the list is long enough
 * that what belongs here should not be hunted for among what does not.
 */
const addableSkills = computed(() => {
    const available = prop.availableSkills ?? [];

    return [
        { label: 'This era', skills: available.filter(inEra) },
        { label: 'Another era', skills: available.filter((skill) => !inEra(skill)) },
    ].filter((group) => group.skills.length > 0);
});

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

            <SwitchGroup as="div" class="flex items-center gap-2">
                <SwitchLabel class="text-sm font-medium text-cthulhu-green-800">Relevant only</SwitchLabel>
                <Switch
                    v-model="relevantOnly"
                    :class="[
                        relevantOnly ? 'bg-cthulhu-yellow-500' : 'bg-parchment-400',
                        'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent ring-1 ring-inset ring-parchment-500/40 transition-colors focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-yellow-500',
                    ]"
                >
                    <span
                        aria-hidden="true"
                        :class="[
                            relevantOnly ? 'translate-x-5' : 'translate-x-0',
                            'pointer-events-none inline-block size-5 transform rounded-full bg-parchment-50 shadow transition',
                        ]"
                    />
                </Switch>
            </SwitchGroup>

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

        <p v-if="unimprovedCount > 0" class="field-hint mb-3">
            Showing improved skills only.
            {{ unimprovedCount }} {{ unimprovedCount === 1 ? 'skill sits' : 'skills sit' }} at the starting value —
            search by name to reach one, or switch “Relevant only” off.
        </p>

        <p v-if="outOfEraCount > 0" class="field-hint mb-3">
            {{ outOfEraCount }} {{ outOfEraCount === 1 ? 'skill belongs' : 'skills belong' }} to another era
            and {{ outOfEraCount === 1 ? 'is' : 'are' }} put away — search by name to reach one, or give it
            a value and it stays out.
        </p>

        <p v-if="editable && hiddenCount > 0" class="field-hint mb-3">
            {{ hiddenCount }} hidden {{ hiddenCount === 1 ? 'skill is' : 'skills are' }} greyed out below.
            Open one and tick “Show on sheet” to bring it back.
        </p>

        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            <!--
                The whole box opens the editor, not just the numbers — on a phone
                the three cells are a small target. It becomes a real button when
                there is something to open, so it takes focus and answers the
                keyboard like one.
            -->
            <component
                :is="canEdit ? 'button' : 'div'"
                v-for="skill in visibleSkills"
                :key="skill.id"
                :type="canEdit ? 'button' : null"
                :title="canEdit ? `Adjust ${skill.display_name}` : null"
                class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-left transition"
                :class="[
                    skill.pivot.show
                        ? 'bg-parchment-50 ring-1 ring-parchment-300'
                        : 'bg-parchment-200/40 opacity-60 outline-dashed outline-1 outline-offset-0 outline-parchment-500',
                    canEdit
                        ? 'cursor-pointer hover:ring-2 hover:ring-cthulhu-yellow-500 focus-visible:ring-2 focus-visible:ring-cthulhu-yellow-500'
                        : '',
                ]"
                @click="openEditModal(skill)"
            >
                <span class="flex min-w-0 items-center gap-1.5">
                    <EyeSlashIcon
                        v-if="!skill.pivot.show"
                        class="size-4 shrink-0 text-cthulhu-green-500"
                        aria-hidden="true"
                    />
                    <span class="truncate text-sm font-semibold text-cthulhu-green-900">{{ skill.display_name }}</span>
                    <span v-if="!skill.pivot.show" class="sr-only">(hidden from the sheet)</span>

                    <EraChips v-if="!inEra(skill)" :eras="skill.eras" :options="eras" class="shrink-0" />

                    <TallyMarks
                        v-if="skill.pivot.experience > 0"
                        :count="skill.pivot.experience"
                        class="shrink-0 text-sm"
                        :class="canImprove(skill) ? 'text-cthulhu-blood-500' : 'text-cthulhu-yellow-600'"
                        :title="canImprove(skill)
                            ? `${skill.pivot.experience} experience checks — ready to improve`
                            : `${skill.pivot.experience} of ${improveAt(skill.pivot.value)} experience checks`"
                    />
                </span>

                <RegularHalfFifth class="!w-[9rem] shrink-0" :skill-value="skill.pivot.value" />
            </component>
        </div>

        <p v-if="visibleSkills.length === 0" class="py-8 text-center text-sm text-cthulhu-green-500">
            <template v-if="query.trim()">No skills match “{{ query }}”.</template>
            <template v-else-if="unimprovedCount > 0">
                Nothing improved yet. Switch “Relevant only” off to see all {{ unimprovedCount }} skills.
            </template>
            <template v-else-if="outOfEraCount > 0">
                Every skill on this sheet belongs to another era.
            </template>
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
                    <optgroup v-for="group in addableSkills" :key="group.label" :label="group.label">
                        <option v-for="skill in group.skills" :key="skill.id" :value="skill.slug">
                            {{ skill.display_name }} (base {{ skill.starting_value }})
                        </option>
                    </optgroup>
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
            <!-- The handbook's description is a paragraph, so on a phone this panel scrolls
                 rather than pushing the value and the checks off the screen. -->
            <div class="max-h-[85vh] overflow-y-auto bg-parchment-100 p-6">
                <label for="skill_value" class="field-label">{{ skillForm.display_name }}</label>

                <p v-if="editingSkill && !inEra(editingSkill)" class="field-hint">
                    A {{ outOfEraLabel(editingSkill) }} skill. It stays put away on this sheet until it has
                    a value above its starting one.
                </p>

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
                </div>

                <div class="mt-4 border-t border-parchment-300 pt-3">
                    <p class="eyebrow">Experience checks</p>

                    <div class="mt-2 flex items-center gap-2">
                        <button
                            type="button"
                            class="btn-secondary btn-sm w-9 !px-0 text-base leading-none"
                            aria-label="One experience check fewer"
                            :disabled="experience === 0 || changingExperience"
                            @click="changeExperience('experience.decrement')"
                        >
                            −
                        </button>

                        <span
                            class="flex h-9 min-w-32 flex-1 items-center rounded-md bg-parchment-50 px-3 ring-1 ring-parchment-300"
                            :class="readyToImprove ? 'text-cthulhu-blood-500' : 'text-cthulhu-yellow-600'"
                        >
                            <TallyMarks v-if="experience > 0" :count="experience" class="text-xl" />
                            <span v-else class="field-hint">No checks yet</span>
                        </span>

                        <button
                            type="button"
                            class="btn-secondary btn-sm w-9 !px-0 text-base leading-none"
                            aria-label="One experience check more"
                            :disabled="changingExperience"
                            @click="changeExperience('experience.increment')"
                        >
                            +
                        </button>

                        <button
                            v-if="experience > 0"
                            type="button"
                            class="btn-ghost btn-sm"
                            :disabled="changingExperience"
                            @click="changeExperience('experience.reset')"
                        >
                            Clear
                        </button>
                    </div>

                    <p class="field-hint mt-2">
                        <template v-if="readyToImprove">
                            Ready to improve — roll for it, then clear the marks.
                        </template>
                        <template v-else>
                            Ready to improve at {{ experienceThreshold }}
                            {{ experienceThreshold === 1 ? 'check' : 'checks' }}, a tenth of {{ skillForm.value }}.
                        </template>
                    </p>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2">
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
