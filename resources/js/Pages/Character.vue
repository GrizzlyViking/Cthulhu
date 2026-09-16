<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Skills from '@/Pages/Components/Character/Skills.vue';
import Equipment from '@/Pages/Components/Character/Equipment.vue';
import Characteristics from '@/Pages/Components/Character/Characteristics.vue';
import Vitals from '@/Pages/Components/Character/Vitals.vue';
import { computed, ref } from 'vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import Backstory from '@/Pages/Components/Character/Backstory.vue';
import BackstoryTab from '@/Pages/Components/Character/BackstoryTab.vue';
import Dropdown from '@/Pages/Components/Dropdown.vue';
import Tabs from '@/Components/Tabs.vue';
import Modal from '@/Components/Modal.vue';
import { ArrowUturnLeftIcon, BoltIcon, BookOpenIcon, IdentificationIcon, PrinterIcon, TrashIcon, UserIcon } from '@heroicons/vue/20/solid';
import { useRoles } from '@/Pages/Composables/useRoles.js';
import { useCharacterImage } from '@/Pages/Composables/useCharacterImage.js';
import Notepad from '@/Pages/Components/Character/Notepad.vue';

const { isKeeper } = useRoles();

const prop = defineProps({
    character: Object,
    notepad: { type: Object, default: () => ({ canView: false, canEdit: false, canSetVisibility: false, visibility: 'everyone', content: null }) },
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

/*
 * Deleting an investigator keeps them. The sheet can still be opened — stamped
 * across, frozen, and carrying the two ways out of it — and every route that
 * would change one refuses a deleted sheet outright, so the editing affordances
 * come off with `canEdit` rather than being left to fail one at a time.
 */
const deleted = computed(() => prop.character.is_deleted === true);

const deletedOn = computed(() =>
    prop.character.deleted_at
        ? new Date(prop.character.deleted_at).toLocaleDateString(undefined, {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        })
        : null
);

const deleteCharacter = () => {
    const kept =
        `Delete ${prop.character.name}? The sheet is kept — struck through in the list — with every ` +
        'skill, belonging and campaign intact, and can be restored from it.';

    if (confirm(kept)) {
        router.delete(route('character.destroy', {
            character: prop.character.slug,
        }));
    }
};

const restoreCharacter = () => {
    router.put(route('character.restore', { character: prop.character.slug }));
};

const purgeCharacter = () => {
    const forGood =
        `Delete ${prop.character.name} completely? The sheet goes, and their skills, everything they ` +
        'carried and every campaign they were in go with it. This cannot be undone.';

    if (confirm(forGood)) {
        router.delete(route('character.purge', { character: prop.character.slug }));
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

const canEdit = computed(() => {
    if (deleted.value) {
        return false;
    }

    return page.props.auth.user.id === prop.character.user_id || isKeeper.value;
});

/*
 * The two pictures the sheet carries. Both are shrunk in the browser before they
 * are sent — see the composable — so a photograph straight off a phone no longer
 * meets the server's upload limit and vanishes without a word.
 */
const {
    uploading: uploadingImage,
    errors: imageErrors,
    upload: uploadImage,
    clear: clearImage,
} = useCharacterImage(computed(() => prop.character.slug));

/**
 * The two cards under Manage sheet. The hint says what each picture is *for*,
 * because that is the whole reason there are two of them.
 */
const pictures = [
    {
        shape: 'portrait',
        label: 'Portrait',
        hint: 'The investigator themselves. It fills the frame at the top of the sheet, cropped from the bottom.',
        column: 'avatar',
        frame: 'aspect-[3/4] w-12',
        // Taking the likeness off leaves the masthead with no frame at all,
        // which is a fine way for a sheet to look.
        revert: 'Remove',
    },
    {
        shape: 'banner',
        label: 'Backdrop',
        hint: 'The scene behind the name. A landscape picture suits the shape of it best.',
        column: 'banner',
        frame: 'aspect-[16/9] w-20',
        revert: 'Use the default',
    },
];

const thumbnail = (column) => (prop.character[column] ? '/storage/' + prop.character[column] : null);

/* The backdrop card shows the house picture when no other has been uploaded,
   so *Use the default* has something to point at. */
const preview = (picture) =>
    thumbnail(picture.column) ?? (picture.shape === 'banner' ? '/images/cthulhu_man_reading.jpeg' : null);

const updateUser = (event) => {
    router.put(route('character.update', { character: prop.character.slug }), {
        user_id: event.id,
    }, { preserveScroll: true });
};

</script>

<template>
    <Head :title="prop.character.name" />

    <AuthenticatedLayout>
        <div class="page">
            <div class="relative">
                <div class="space-y-5" :class="{ 'opacity-60': deleted }">
                    <Backstory :character="prop.character" :editable="editable" :can-edit="canEdit" />

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
                            <Notepad :key="prop.character.slug" :character-slug="prop.character.slug" :notepad="prop.notepad" />
                        </template>
                    </Tabs>

                    <!--
                        Printing and the edit switch are about the sheet rather than
                        part of it, so they sit under everything, quiet, on the page's
                        own ground. The switch is last because what it reveals — Manage
                        sheet — opens directly beneath it.
                    -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <!--
                            The printable sheet is a plain Blade document, not an Inertia page,
                            so it has to be a real link rather than <Link>/router.visit().
                        -->
                        <a
                            :href="route('character.sheet', { character: prop.character.slug })"
                            target="_blank"
                            rel="noopener"
                            class="btn-ghost-on-dark btn-sm"
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

                    <!-- Sheet management, only while editing -->
                    <section v-if="editable" class="panel p-4 sm:p-5">
                        <h2 class="mb-4 text-base font-semibold text-cthulhu-green-900">Manage sheet</h2>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div v-for="picture in pictures" :key="picture.shape" class="card flex flex-col gap-2">
                                <div>
                                    <p class="eyebrow">{{ picture.label }}</p>
                                    <p class="field-hint">{{ picture.hint }}</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="shrink-0 overflow-hidden rounded-md bg-cthulhu-green-900 ring-1 ring-parchment-400"
                                        :class="picture.frame"
                                    >
                                        <img
                                            v-if="preview(picture)"
                                            :src="preview(picture)"
                                            alt=""
                                            class="size-full object-cover object-top"
                                        />
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        <label :for="`upload_${picture.shape}`" class="btn-secondary btn-sm cursor-pointer">
                                            {{ uploadingImage === picture.shape ? 'Uploading…' : (thumbnail(picture.column) ? 'Change' : 'Upload') }}
                                        </label>
                                        <input
                                            :id="`upload_${picture.shape}`"
                                            type="file"
                                            accept="image/*"
                                            class="hidden"
                                            :disabled="uploadingImage !== null"
                                            @change="uploadImage(picture.shape, $event.target.files[0]); $event.target.value = ''"
                                        />

                                        <!-- Nothing is lost that cannot be uploaded again, so this
                                             takes the picture off without a dialog in the way. -->
                                        <button
                                            v-if="thumbnail(picture.column)"
                                            type="button"
                                            class="btn-ghost btn-sm"
                                            :disabled="uploadingImage !== null"
                                            @click="clearImage(picture.shape)"
                                        >
                                            {{ picture.revert }}
                                        </button>
                                    </div>
                                </div>

                                <p v-if="imageErrors[picture.shape]" class="field-error">{{ imageErrors[picture.shape] }}</p>
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
                                    <p class="field-hint">
                                        The sheet is kept, struck through in the list, and can be restored — or
                                        deleted for good — from the sheet itself.
                                    </p>
                                </div>
                                <button type="button" class="btn-danger btn-sm self-start" @click="deleteCharacter">
                                    Delete {{ prop.character.name }}
                                </button>
                            </div>
                        </div>
                    </section>
                </div>

                <!--
                    Deleting keeps the sheet, so it is shown rather than hidden: the
                    stamp goes over it and the investigator reads through, dimmed and
                    frozen. The stamp lets clicks through — there is nothing live
                    beneath it — and only the card takes them.
                -->
                <div v-if="deleted" class="pointer-events-none absolute inset-0 z-20">
                    <div class="sticky top-0 flex h-screen flex-col items-center justify-center gap-6 px-4">
                        <!--
                            Clipped here rather than on the veil: an `overflow` on
                            the veil would make it the scroll container, and the
                            sticky block would stop following the page down.
                        -->
                        <div class="flex w-full justify-center overflow-hidden py-8">
                            <p
                                aria-hidden="true"
                                class="display -rotate-12 select-none whitespace-nowrap text-[14vw] leading-none text-cthulhu-blood-300/40"
                            >
                                Deleted
                            </p>
                        </div>

                        <div class="pointer-events-auto panel w-full max-w-md p-5 shadow-raised">
                            <p class="eyebrow">Deleted<span v-if="deletedOn"> · {{ deletedOn }}</span></p>
                            <p class="mt-1 text-sm text-cthulhu-green-800">
                                {{ prop.character.name }} is kept whole — every skill, everything they carried
                                and every campaign they were played in — until you say otherwise. Nothing on the
                                sheet can be changed while it is deleted.
                            </p>

                            <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                <button type="button" class="btn-primary" @click="restoreCharacter">
                                    <ArrowUturnLeftIcon class="size-4" aria-hidden="true" />
                                    Restore
                                </button>
                                <button type="button" class="btn-danger" @click="purgeCharacter">
                                    <TrashIcon class="size-4" aria-hidden="true" />
                                    Delete completely
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
