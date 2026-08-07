<script setup>
import { useForm } from '@inertiajs/vue3';
import { StarIcon } from '@heroicons/vue/20/solid';
import { KEY_CONNECTION_CATEGORIES } from '@/Pages/Components/Wizard/wizardData.js';

const prop = defineProps({ character: Object, canEdit: Boolean });

const saved = prop.character.backstory ?? {};

/* The six core entries share their labels with the wizard's key connection categories. */
const mainFields = [
    'personal_description',
    'ideology',
    'significant_people',
    'meaningful_locations',
    'treasured_possessions',
    'traits',
].map((key, index) => ({ key, label: KEY_CONNECTION_CATEGORIES[index] }));

const playFields = [
    { key: 'injuries_scars', label: 'Injuries & Scars' },
    { key: 'phobias_manias', label: 'Phobias & Manias' },
];

const form = useForm({
    personal_description: saved.personal_description ?? '',
    ideology: saved.ideology ?? '',
    significant_people: saved.significant_people ?? '',
    meaningful_locations: saved.meaningful_locations ?? '',
    treasured_possessions: saved.treasured_possessions ?? '',
    traits: saved.traits ?? '',
    injuries_scars: saved.injuries_scars ?? '',
    phobias_manias: saved.phobias_manias ?? '',
    key_connection: saved.key_connection ?? '',
    gear: saved.gear ?? '',
});

const save = () => {
    form.put(route('character.backstory.update', { character: prop.character.slug }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <section class="panel p-4 sm:p-5">
        <div class="flex flex-col gap-4">
            <!-- Key connection: the entry the Keeper cannot destroy without a saving throw -->
            <div class="card-marked">
                <div class="flex items-center gap-2">
                    <StarIcon class="size-5 shrink-0 text-cthulhu-yellow-700" aria-hidden="true" />
                    <h3 class="text-sm font-semibold text-cthulhu-green-900">Key connection</h3>
                </div>
                <p class="field-hint">
                    The one entry that gives this investigator's life meaning — it can aid Sanity
                    recovery, and the Keeper cannot take it away without a chance to save it.
                </p>
                <template v-if="prop.canEdit">
                    <textarea
                        id="backstory-key_connection"
                        v-model="form.key_connection"
                        rows="4"
                        aria-label="Key connection"
                        class="field mt-2"
                    ></textarea>
                    <p v-if="form.errors.key_connection" class="field-error">{{ form.errors.key_connection }}</p>
                </template>
                <p v-else class="mt-2 whitespace-pre-line text-sm text-cthulhu-green-800">
                    <template v-if="saved.key_connection">{{ saved.key_connection }}</template>
                    <span v-else class="italic text-cthulhu-green-500">Not yet written</span>
                </p>
            </div>

            <!-- The six core backstory entries -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="field in mainFields" :key="field.key" class="card">
                    <label :for="`backstory-${field.key}`" class="field-label">{{ field.label }}</label>
                    <template v-if="prop.canEdit">
                        <textarea
                            :id="`backstory-${field.key}`"
                            v-model="form[field.key]"
                            rows="7"
                            class="field mt-2"
                        ></textarea>
                        <p v-if="form.errors[field.key]" class="field-error">{{ form.errors[field.key] }}</p>
                    </template>
                    <p v-else class="mt-2 whitespace-pre-line text-sm text-cthulhu-green-800">
                        <template v-if="saved[field.key]">{{ saved[field.key] }}</template>
                        <span v-else class="italic text-cthulhu-green-500">Not yet written</span>
                    </p>
                </div>
            </div>

            <!-- Marks of play: filled in as sanity crumbles -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="field in playFields" :key="field.key" class="card">
                    <label :for="`backstory-${field.key}`" class="field-label">{{ field.label }}</label>
                    <template v-if="prop.canEdit">
                        <textarea
                            :id="`backstory-${field.key}`"
                            v-model="form[field.key]"
                            rows="5"
                            class="field mt-2"
                        ></textarea>
                        <p v-if="form.errors[field.key]" class="field-error">{{ form.errors[field.key] }}</p>
                    </template>
                    <p v-else class="mt-2 whitespace-pre-line text-sm text-cthulhu-green-800">
                        <template v-if="saved[field.key]">{{ saved[field.key] }}</template>
                        <span v-else class="italic text-cthulhu-green-500">Not yet written</span>
                    </p>
                </div>
            </div>

            <!-- Gear -->
            <div class="card">
                <label for="backstory-gear" class="field-label">Gear &amp; possessions</label>
                <template v-if="prop.canEdit">
                    <textarea
                        id="backstory-gear"
                        v-model="form.gear"
                        rows="6"
                        class="field mt-2"
                    ></textarea>
                    <p v-if="form.errors.gear" class="field-error">{{ form.errors.gear }}</p>
                </template>
                <p v-else class="mt-2 whitespace-pre-line text-sm text-cthulhu-green-800">
                    <template v-if="saved.gear">{{ saved.gear }}</template>
                    <span v-else class="italic text-cthulhu-green-500">Not yet written</span>
                </p>
            </div>

            <div v-if="prop.canEdit" class="flex justify-end">
                <button type="button" class="btn-primary" :disabled="form.processing" @click="save">
                    {{ form.processing ? 'Saving…' : 'Save backstory' }}
                </button>
            </div>
        </div>
    </section>
</template>
