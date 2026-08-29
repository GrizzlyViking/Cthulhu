<script setup>
import { useForm } from '@inertiajs/vue3';
import { BookOpenIcon, IdentificationIcon, MapPinIcon, StarIcon } from '@heroicons/vue/20/solid';
import { KEY_CONNECTION_CATEGORIES } from '@/Pages/Components/Wizard/wizardData.js';
import { useAdjustAttribute } from '@/Pages/Composables/useAdjustAttribute.js';

const prop = defineProps({ character: Object, canEdit: Boolean });

const { updateAttribute } = useAdjustAttribute('character');

/*
 * Who the investigator is, and where they come from. These used to sit on the
 * masthead over the backdrop, which left the name competing with six fields for
 * the same picture. They are backstory, so this is where they belong — and they
 * save as they are typed, the way every other edit-in-place field on the sheet
 * does, rather than waiting for the button at the foot of the tab.
 *
 * @type {{key: string, label: string, type: string, options?: string[]}[]}
 */
const identity = [
    { key: 'age', label: 'Age', type: 'number' },
    // Gender is an enum with a check constraint behind it, so it is picked
    // rather than typed — anything else is refused by the database.
    { key: 'gender', label: 'Gender', type: 'text', options: ['Male', 'Female', 'Other'] },
];

const background = [
    { key: 'occupation', label: 'Occupation', type: 'text' },
    { key: 'residence', label: 'Residence', type: 'text' },
    { key: 'birthplace', label: 'Birthplace', type: 'text' },
];

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
    my_story: saved.my_story ?? '',
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
            <!-- Who they are and where they are from, ahead of the story about it -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="card">
                    <div class="flex items-center gap-2">
                        <IdentificationIcon class="size-5 shrink-0 text-cthulhu-yellow-700" aria-hidden="true" />
                        <h3 class="text-sm font-semibold text-cthulhu-green-900">Character</h3>
                    </div>
                    <p class="field-hint">Saved as you type. The occupation reads above the name on the sheet.</p>
                    <div class="mt-3 flex flex-col gap-2">
                        <div v-for="row in identity" :key="row.key" class="grid grid-cols-[6rem_1fr] items-center gap-2">
                            <label :for="`identity-${row.key}`" class="text-sm text-cthulhu-green-700">{{ row.label }}</label>
                            <select
                                v-if="row.options"
                                :id="`identity-${row.key}`"
                                v-model="prop.character[row.key]"
                                class="field py-1.5 text-sm"
                                :disabled="!prop.canEdit"
                                @change="updateAttribute(row.key, $event)"
                            >
                                <option v-for="option in row.options" :key="option" :value="option">{{ option }}</option>
                            </select>
                            <input
                                v-else
                                :id="`identity-${row.key}`"
                                v-model="prop.character[row.key]"
                                :type="row.type"
                                class="field py-1.5 text-sm"
                                :class="row.type === 'number' ? 'tabular' : ''"
                                :disabled="!prop.canEdit"
                                @input="updateAttribute(row.key, $event)"
                            />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="flex items-center gap-2">
                        <MapPinIcon class="size-5 shrink-0 text-cthulhu-yellow-700" aria-hidden="true" />
                        <h3 class="text-sm font-semibold text-cthulhu-green-900">Background</h3>
                    </div>
                    <p class="field-hint">What they do, and the two places they answer to.</p>
                    <div class="mt-3 flex flex-col gap-2">
                        <div v-for="row in background" :key="row.key" class="grid grid-cols-[6rem_1fr] items-center gap-2">
                            <label :for="`background-${row.key}`" class="text-sm text-cthulhu-green-700">{{ row.label }}</label>
                            <input
                                :id="`background-${row.key}`"
                                v-model="prop.character[row.key]"
                                :type="row.type"
                                class="field py-1.5 text-sm"
                                :disabled="!prop.canEdit"
                                @input="updateAttribute(row.key, $event)"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- My story: the long one, so it takes the top of the tab and the room to run on -->
            <div class="card-marked">
                <div class="flex items-center gap-2">
                    <BookOpenIcon class="size-5 shrink-0 text-cthulhu-yellow-700" aria-hidden="true" />
                    <h3 class="text-sm font-semibold text-cthulhu-green-900">My story</h3>
                </div>
                <p class="field-hint">
                    The investigator's life in their own words — where they came from, what they have
                    seen, and what brought them to the table. Write as much of it as you like.
                </p>
                <template v-if="prop.canEdit">
                    <textarea
                        id="backstory-my_story"
                        v-model="form.my_story"
                        rows="14"
                        aria-label="My story"
                        class="field mt-2"
                    ></textarea>
                    <p v-if="form.errors.my_story" class="field-error">{{ form.errors.my_story }}</p>
                </template>
                <p v-else class="mt-2 whitespace-pre-line text-sm text-cthulhu-green-800">
                    <template v-if="saved.my_story">{{ saved.my_story }}</template>
                    <span v-else class="italic text-cthulhu-green-500">Not yet written</span>
                </p>
            </div>

            <!-- Key connection, then the six core entries — a column each -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                            rows="7"
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
