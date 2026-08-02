<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Skills from '@/Pages/Components/Character/Skills.vue';
import Weapons from '@/Pages/Components/Character/Weapons.vue';
import Characteristics from '@/Pages/Components/Character/Characteristics.vue';
import Vitals from '@/Pages/Components/Character/Vitals.vue';
import { computed, ref } from 'vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import Backstory from '@/Pages/Components/Character/Backstory.vue';
import BackstoryTab from '@/Pages/Components/Character/BackstoryTab.vue';
import Dropdown from '@/Pages/Components/Dropdown.vue';
import Tabs from '@/Components/Tabs.vue';
import { BoltIcon, BookOpenIcon, IdentificationIcon, PrinterIcon, UserIcon } from '@heroicons/vue/20/solid';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const prop = defineProps({ character: Object, availableSkills: Array });
const editable = ref(false);
const tabs = [
    { name: 'Skills', icon: UserIcon },
    { name: 'Weapons', icon: BoltIcon },
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

const appendAllMissingSkills = () => {
    router.get(route('character.append.missing.skills', { character: prop.character.slug }));
};

const form = useForm({
    avatar: null,
});

const notesForm = useForm({
    notes: prop.character.notes,
});

const handleFileUpload = () => {
    form.post(route('upload.avatar', { character: prop.character.slug }), { preserveScroll: true });
};

const canEdit = computed(() => {
    return page.props.auth.user.id === prop.character.user_id || page.props.auth.user.role === 'keeper';
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
                    />
                </template>

                <template #Weapons>
                    <Weapons :character="prop.character" :editable="editable" :can-edit="canEdit" />
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
                            <p class="field-hint">Add every canonical skill this sheet is missing.</p>
                        </div>
                        <button type="button" class="btn-secondary btn-sm self-start" @click="appendAllMissingSkills">
                            Append missing skills
                        </button>
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
