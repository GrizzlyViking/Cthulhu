<script setup>
import { computed, defineAsyncComponent } from 'vue';
import { useForm } from '@inertiajs/vue3';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    characterSlug: { type: String, required: true },
    notepad: { type: Object, required: true },
});

// Quill stays lazy so the character page retains its Vite manifest entry.
const QuillEditor = defineAsyncComponent(() => import('@vueup/vue-quill').then(module => module.QuillEditor));
const levels = [
    { value: 'everyone', label: 'Everyone at the table', hint: 'Anyone in your group who can open this sheet can read these notes.' },
    { value: 'keeper', label: 'Player and Keeper', hint: 'Only the player and Keepers in this group can read these notes.' },
    { value: 'private', label: 'Player’s eyes only', hint: 'Only the player can read these notes. Keepers and admins cannot see them.' },
];
const form = useForm({ notes: props.notepad.content ?? '', notes_visibility: props.notepad.visibility });
const level = computed({
    get: () => Math.max(0, levels.findIndex(item => item.value === form.notes_visibility)),
    set: (index) => { form.notes_visibility = levels[Number(index)].value; },
});
const save = () => form.transform(data => props.notepad.canSetVisibility ? data : { notes: data.notes })
    .put(route('character.notes.update', { character: props.characterSlug }), { preserveScroll: true });
</script>

<template>
    <section class="panel flex flex-col gap-4 p-4 sm:p-5">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <h2 class="text-base font-semibold text-cthulhu-green-900">Notepad</h2>
            <span class="chip">{{ levels.find(item => item.value === notepad.visibility)?.label }}</span>
        </div>
        <template v-if="notepad.canView">
            <div v-if="notepad.canSetVisibility" class="card">
                <label for="notes-visibility" class="field-label">Who can read these notes?</label>
                <input id="notes-visibility" v-model.number="level" type="range" min="0" max="2" step="1" :aria-valuetext="levels[level].label" aria-describedby="notes-visibility-hint" class="mt-2 h-10 w-full cursor-pointer accent-cthulhu-green-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-green-800" />
                <div class="grid grid-cols-3 gap-2 text-xs text-cthulhu-green-700" aria-hidden="true">
                    <span>Everyone</span><span class="text-center">Player + Keeper</span><span class="text-right">Player only</span>
                </div>
                <p id="notes-visibility-hint" class="field-hint mt-3">{{ levels[level].hint }} The setting is saved with your notes.</p>
                <p v-if="form.errors.notes_visibility" class="field-error">{{ form.errors.notes_visibility }}</p>
            </div>
            <div>
                <p id="notepad-label" class="sr-only">Investigator notes</p>
                <QuillEditor v-model:content="form.notes" content-type="html" theme="snow" :read-only="!notepad.canEdit" :toolbar="notepad.canEdit ? 'essential' : []" class="notepad" aria-labelledby="notepad-label" />
            </div>
            <p v-if="form.errors.notes" class="field-error">{{ form.errors.notes }}</p>
            <div v-if="notepad.canEdit" class="flex items-center justify-end gap-3">
                <span v-if="form.recentlySuccessful" role="status" class="text-sm text-cthulhu-green-500">Notes saved.</span>
                <button type="button" class="btn-primary" :disabled="form.processing" @click="save">{{ form.processing ? 'Saving…' : 'Save notes' }}</button>
            </div>
        </template>
        <p v-else class="text-sm text-cthulhu-green-500">These notes are private. The player decides who can read them.</p>
    </section>
</template>
