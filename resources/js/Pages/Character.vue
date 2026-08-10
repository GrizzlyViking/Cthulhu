<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Skills from '@/Pages/Components/Character/Skills.vue';
import Equipment from '@/Pages/Components/Character/Equipment.vue';
import Characteristics from '@/Pages/Components/Character/Characteristics.vue';
import Vitals from '@/Pages/Components/Character/Vitals.vue';
import { computed, defineAsyncComponent, ref } from 'vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import Backstory from '@/Pages/Components/Character/Backstory.vue';
import BackstoryTab from '@/Pages/Components/Character/BackstoryTab.vue';
import Dropdown from '@/Pages/Components/Dropdown.vue';
import Tabs from '@/Components/Tabs.vue';
import Modal from '@/Components/Modal.vue';
import { BoltIcon, BookOpenIcon, IdentificationIcon, PrinterIcon, UserIcon } from '@heroicons/vue/20/solid';
import { useRoles } from '@/Pages/Composables/useRoles.js';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const { isKeeper } = useRoles();

/*
 * Quill is only needed on the Notepad tab, and a static import of
 * @vueup/vue-quill makes rollup drop this page's chunk facade — the page then
 * vanishes from the Vite manifest and the route 500s. Load it lazily instead.
 */
const QuillEditor = defineAsyncComponent(() => import('@vueup/vue-quill').then((m) => m.QuillEditor));

const prop = defineProps({
    character: Object,
    availableSkills: Array,
    storageLocations: Array,
    alwaysRelevantSkills: Array,
    /** The era of the game being played, and every era the server knows about. */
    era: String,
    eras: Array,
    /** The group's campaigns, so this investigator can be moved between them. */
    games: Array,
});
const editable = ref(false);
const tabs = [
    { name: 'Skills', icon: UserIcon },
    { name: 'Equipment', icon: BoltIcon },
    { name: 'Backstory', icon: IdentificationIcon },
    { name: 'Notepad', icon: BookOpenIcon },
];
const page = usePage();

const deleteCharacter = () => {
    if (confirm(`Permanently delete ${prop.character.name}? This cannot be undone.`)) {
        router.delete(route('character.destroy', {
            character: prop.character.slug,
        }));
    }
};

/*
 * Creating a skill the canonical list lacks. The skill is shared by every
 * group on the server; it is attached to this sheet on the way out.
 */
const showSkillModal = ref(false);

const skillForm = useForm({
    display_name: '',
    description: '',
    starting_value: 1,
    value_obtained: null,
    character_id: prop.character.id,
});

const openSkillModal = () => {
    skillForm.reset();
    skillForm.clearErrors();
    showSkillModal.value = true;
};

const createSkill = () => {
    skillForm.post(route('skill.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showSkillModal.value = false;
            skillForm.reset();
        },
    });
};

const form = useForm({
    avatar: null,
});

const notesForm = useForm({
    notes: prop.character.notes,
});

/*
 * Which campaigns this investigator is played in. Ticking one saves straight
 * away — there is nothing to confirm, and the nav regroups on the way back.
 */
const gamesForm = useForm({
    games: (prop.character.games ?? []).map((game) => game.id),
});

const toggleGame = (gameId) => {
    const index = gamesForm.games.indexOf(gameId);

    if (index === -1) {
        gamesForm.games.push(gameId);
    } else {
        gamesForm.games.splice(index, 1);
    }

    gamesForm.put(route('character.games.update', { character: prop.character.slug }), {
        preserveScroll: true,
    });
};

const handleFileUpload = () => {
    form.post(route('upload.avatar', { character: prop.character.slug }), { preserveScroll: true });
};

const canEdit = computed(() => {
    return page.props.auth.user.id === prop.character.user_id || isKeeper.value;
});

const avatarThumb = computed(() =>
    prop.character.avatar ? '/storage/' + prop.character.avatar : '/images/cthulhu_man_reading.jpeg'
);

const updateUser = (event) => {
    router.put(route('character.update', { character: prop.character.slug }), {
        user_id: event.id,
    }, { preserveScroll: true });
};

const saveNotes = () => {
    notesForm.put(route('character.update', { character: prop.character.slug }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="prop.character.name" />

    <AuthenticatedLayout>
        <div class="page">
            <Backstory :character="prop.character" :editable="editable">
                <template #actions>
                    <div class="flex flex-wrap items-center gap-4">
                    <!--
                        The printable sheet is a plain Blade document, not an Inertia page,
                        so it has to be a real link rather than <Link>/router.visit().
                    -->
                    <a
                        :href="route('character.sheet', { character: prop.character.slug })"
                        target="_blank"
                        rel="noopener"
                        class="btn-secondary btn-sm"
                    >
                        <PrinterIcon class="size-4" aria-hidden="true" />
                        Print sheet
                    </a>

                    <SwitchGroup v-if="canEdit" as="div" class="flex items-center gap-3">
                        <SwitchLabel class="text-sm font-medium text-cthulhu-green-200">Edit sheet</SwitchLabel>
                        <Switch
                            v-model="editable"
                            :class="[
                                editable ? 'bg-cthulhu-yellow-500' : 'bg-cthulhu-green-800',
                                'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent ring-1 ring-inset ring-parchment-100/20 transition-colors focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-yellow-500',
                            ]"
                        >
                            <span
                                aria-hidden="true"
                                :class="[
                                    editable ? 'translate-x-5' : 'translate-x-0',
                                    'pointer-events-none inline-block size-5 transform rounded-full bg-parchment-50 shadow transition',
                                ]"
                            />
                        </Switch>
                    </SwitchGroup>
                    </div>
                </template>
            </Backstory>

            <Vitals :character="prop.character" :can-edit="canEdit" />

            <Characteristics :character="prop.character" :editable="editable" :can-edit="canEdit" />

            <Tabs :tabs="tabs">
                <template #Skills>
                    <Skills
                        :character="prop.character"
                        :can-edit="canEdit"
                        :editable="editable"
                        :available-skills="prop.availableSkills ?? []"
                        :always-relevant-skills="prop.alwaysRelevantSkills ?? []"
                        :era="prop.era"
                        :eras="prop.eras ?? []"
                    />
                </template>

                <template #Equipment>
                    <Equipment
                        :character="prop.character"
                        :editable="editable"
                        :can-edit="canEdit"
                        :storage-locations="prop.storageLocations ?? []"
                        :era="prop.era"
                        :eras="prop.eras ?? []"
                    />
                </template>

                <template #Backstory>
                    <BackstoryTab :character="prop.character" :can-edit="canEdit" />
                </template>

                <template #Notepad>
                    <section class="panel flex flex-col gap-3 p-4 sm:p-5">
                        <h2 class="text-base font-semibold text-cthulhu-green-900">Notepad</h2>
                        <quill-editor
                            v-model:content="notesForm.notes"
                            theme="snow"
                            content-type="html"
                            class="notepad"
                        />
                        <div class="flex justify-end">
                            <button type="button" class="btn-primary" :disabled="notesForm.processing" @click="saveNotes">
                                {{ notesForm.processing ? 'Saving…' : 'Save notes' }}
                            </button>
                        </div>
                    </section>
                </template>
            </Tabs>

            <!-- Sheet management, only while editing -->
            <section v-if="editable" class="panel p-4 sm:p-5">
                <h2 class="mb-4 text-base font-semibold text-cthulhu-green-900">Manage sheet</h2>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="card">
                        <p class="eyebrow">Portrait</p>
                        <div class="mt-2 flex items-center gap-3">
                            <img :src="avatarThumb" alt="" class="size-12 shrink-0 rounded-full object-cover ring-1 ring-parchment-400" />
                            <label for="avatar_upload" class="btn-secondary btn-sm cursor-pointer">Change</label>
                            <input
                                id="avatar_upload"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @input="form.avatar = $event.target.files[0]"
                                @change="handleFileUpload"
                            />
                        </div>
                    </div>

                    <div class="card">
                        <p class="eyebrow">Player</p>
                        <div class="mt-2">
                            <Dropdown
                                :value="prop.character.user_id"
                                :list="page.props.auth.users"
                                :open="editable"
                                :initially-selected="prop.character.user_id"
                                @update:model-value="updateUser"
                            />
                        </div>
                    </div>

                    <div class="card flex flex-col justify-between gap-2">
                        <div>
                            <p class="eyebrow">Skills</p>
                            <p class="field-hint">Create a skill the handbook list lacks.</p>
                        </div>
                        <button type="button" class="btn-secondary btn-sm self-start" @click="openSkillModal">
                            Create skill
                        </button>
                    </div>

                    <div v-if="prop.games?.length" class="card flex flex-col gap-2">
                        <div>
                            <p class="eyebrow">Games</p>
                            <p class="field-hint">The campaigns this investigator is played in.</p>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label
                                v-for="game in prop.games"
                                :key="game.id"
                                class="flex cursor-pointer items-start gap-2.5"
                            >
                                <input
                                    type="checkbox"
                                    class="mt-0.5 size-4 shrink-0 rounded border-parchment-400 bg-parchment-50 text-cthulhu-green-800 focus:ring-cthulhu-green-600"
                                    :checked="gamesForm.games.includes(game.id)"
                                    :disabled="gamesForm.processing"
                                    @change="toggleGame(game.id)"
                                />
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-cthulhu-green-900">
                                        {{ game.name }}
                                        <span v-if="game.active" class="chip-brass ml-1">Playing now</span>
                                    </span>
                                    <span class="block text-xs text-cthulhu-green-500">
                                        {{ prop.eras.find((era) => era.value === game.era)?.short ?? game.era }}
                                    </span>
                                </span>
                            </label>
                        </div>

                        <p v-if="gamesForm.errors.games" class="field-error">{{ gamesForm.errors.games }}</p>
                    </div>

                    <div class="card flex flex-col justify-between gap-2">
                        <div>
                            <p class="eyebrow">Danger zone</p>
                            <p class="field-hint">Deleting an investigator cannot be undone.</p>
                        </div>
                        <button type="button" class="btn-danger btn-sm self-start" @click="deleteCharacter">
                            Delete {{ prop.character.name }}
                        </button>
                    </div>
                </div>
            </section>

            <Modal :show="showSkillModal" max-width="lg" @close="showSkillModal = false">
                <form class="flex flex-col gap-4 bg-parchment-100 p-6" @submit.prevent="createSkill">
                    <div>
                        <h2 class="display text-lg text-cthulhu-green-900">New skill</h2>
                        <p class="field-hint">
                            For anything the handbook list is missing. The skill joins the shared list, so
                            every investigator can take it afterwards — this sheet gets it straight away.
                        </p>
                    </div>

                    <div>
                        <label for="new_skill_name" class="field-label">Name</label>
                        <input
                            id="new_skill_name"
                            v-model="skillForm.display_name"
                            type="text"
                            maxlength="50"
                            autocomplete="off"
                            class="field mt-1"
                            required
                        />
                        <p v-if="skillForm.errors.display_name" class="field-error">{{ skillForm.errors.display_name }}</p>
                        <p v-if="skillForm.errors.slug" class="field-error">{{ skillForm.errors.slug }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="new_skill_base" class="field-label">Base value</label>
                            <input
                                id="new_skill_base"
                                v-model.number="skillForm.starting_value"
                                type="number"
                                min="0"
                                max="100"
                                inputmode="numeric"
                                class="field tabular mt-1"
                                required
                            />
                            <p class="field-hint">What an untrained investigator has.</p>
                            <p v-if="skillForm.errors.starting_value" class="field-error">{{ skillForm.errors.starting_value }}</p>
                        </div>

                        <div>
                            <label for="new_skill_value" class="field-label">Value on this sheet</label>
                            <input
                                id="new_skill_value"
                                v-model.number="skillForm.value_obtained"
                                type="number"
                                min="0"
                                max="100"
                                inputmode="numeric"
                                placeholder="same as base"
                                class="field tabular mt-1"
                            />
                            <p class="field-hint">Leave empty to start at the base value.</p>
                            <p v-if="skillForm.errors.value_obtained" class="field-error">{{ skillForm.errors.value_obtained }}</p>
                        </div>
                    </div>

                    <div>
                        <label for="new_skill_description" class="field-label">Description</label>
                        <textarea
                            id="new_skill_description"
                            v-model="skillForm.description"
                            rows="3"
                            class="field mt-1"
                        ></textarea>
                        <p class="field-hint">Optional. Shown when the skill is opened on a sheet.</p>
                        <p v-if="skillForm.errors.description" class="field-error">{{ skillForm.errors.description }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" class="btn-ghost" @click="showSkillModal = false">Cancel</button>
                        <button type="submit" class="btn-primary" :disabled="skillForm.processing">
                            {{ skillForm.processing ? 'Creating…' : 'Create skill' }}
                        </button>
                    </div>
                </form>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Quill ships its own chrome; reskin it to match the parchment panels. */
:deep(.notepad.ql-container.ql-snow),
:deep(.notepad .ql-container.ql-snow) {
    @apply rounded-b-lg border-0 ring-1 ring-inset ring-parchment-400;
}

:deep(.ql-toolbar.ql-snow) {
    @apply rounded-t-lg border-0 bg-parchment-200 ring-1 ring-inset ring-parchment-400;
}

:deep(.ql-container.ql-snow) {
    @apply rounded-b-lg border-0 bg-parchment-50 ring-1 ring-inset ring-parchment-400;
}

:deep(.ql-editor) {
    @apply min-h-80 rounded-b-lg px-4 py-3 text-cthulhu-green-900;
}

:deep(.ql-editor.ql-blank::before) {
    @apply not-italic text-cthulhu-green-500;
}

:deep(.ql-snow .ql-stroke) {
    stroke: theme('colors.cthulhu-green.800');
}

:deep(.ql-snow .ql-fill) {
    fill: theme('colors.cthulhu-green.800');
}

:deep(.ql-snow .ql-picker) {
    color: theme('colors.cthulhu-green.800');
}
</style>
