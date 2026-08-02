<script setup>
import { computed, reactive, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import HelpDisclosure from '@/Pages/Components/Wizard/HelpDisclosure.vue';
import {
    IDEOLOGY_TABLE,
    KEY_CONNECTION_CATEGORIES,
    LOCATIONS_TABLE,
    PERSONAL_DESCRIPTION_WORDS,
    POSSESSIONS_TABLE,
    SIGNIFICANT_WHO_TABLE,
    SIGNIFICANT_WHY_TABLE,
    TRAITS_TABLE,
} from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    draft: { type: Object, required: true },
});

const emit = defineEmits(['advance']);

const page = usePage();
const processing = ref(false);
const showMore = ref(false);

const saved = props.draft.backstory ?? {};

const form = reactive({
    personal_description: saved.personal_description ?? '',
    ideology: saved.ideology ?? '',
    significant_people: saved.significant_people ?? '',
    meaningful_locations: saved.meaningful_locations ?? '',
    treasured_possessions: saved.treasured_possessions ?? '',
    traits: saved.traits ?? '',
    injuries_scars: saved.injuries_scars ?? '',
    phobias_manias: saved.phobias_manias ?? '',
});

/* Key connection: stored as "Category — detail" in a single string. */
const parseKeyConnection = (value) => {
    if (!value) return { category: '', detail: '' };
    const separator = value.indexOf(' — ');
    if (separator > 0) {
        const category = value.slice(0, separator);
        if (KEY_CONNECTION_CATEGORIES.includes(category)) {
            return { category, detail: value.slice(separator + 3) };
        }
    }
    return { category: '', detail: value };
};

const parsed = parseKeyConnection(saved.key_connection);
const keyCategory = ref(parsed.category);
const keyDetail = ref(parsed.detail);

const keyConnection = computed(() => {
    if (!keyCategory.value && !keyDetail.value) return null;
    return keyCategory.value
        ? `${keyCategory.value} — ${keyDetail.value}`.trim()
        : keyDetail.value;
});

const append = (field, text) => {
    form[field] = form[field] ? `${form[field]}\n${text}` : text;
};

const fields = [
    {
        key: 'personal_description',
        label: 'Personal Description',
        hint: 'A distinct look that sums up your investigator’s appearance.',
        chooser: true,
    },
    {
        key: 'ideology',
        label: 'Ideology / Beliefs',
        hint: 'What does your investigator hold true about the world?',
        table: IDEOLOGY_TABLE,
    },
    {
        key: 'significant_people',
        label: 'Significant People',
        hint: 'Name them — who matters, and why?',
        tables: [
            { title: 'First, who? (roll 1D10)', rows: SIGNIFICANT_WHO_TABLE },
            { title: 'Then, why are they significant? (roll 1D10 again)', rows: SIGNIFICANT_WHY_TABLE },
        ],
    },
    {
        key: 'meaningful_locations',
        label: 'Meaningful Locations',
        hint: 'Places that anchor your investigator to the world.',
        table: LOCATIONS_TABLE,
    },
    {
        key: 'treasured_possessions',
        label: 'Treasured Possessions',
        hint: 'Objects your investigator would run into a burning house for.',
        table: POSSESSIONS_TABLE,
    },
    {
        key: 'traits',
        label: 'Traits',
        hint: 'A defining quality of character.',
        table: TRAITS_TABLE,
    },
];

const submit = () => {
    processing.value = true;
    router.put(
        route('character.wizard.backstory', { character: props.draft.slug }),
        {
            ...form,
            key_connection: keyConnection.value,
            gear: saved.gear ?? null,
        },
        {
            preserveScroll: true,
            onSuccess: () => emit('advance'),
            onFinish: () => (processing.value = false),
        }
    );
};

const textareaClass =
    'block w-full rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600';
</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="panel p-4 sm:p-6">
            <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
                Create your backstory
            </h2>
            <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
                Be specific, emotional and emphatic. Not "my wife" but
                <span class="italic">"My beloved wife Annabel — I couldn't live without her."</span>
                Name people and places, attach a feeling, make it intense. Roll a physical
                1D10 on the inspiration tables and read the matching row, or simply pick one.
            </p>

            <div class="mt-5 flex flex-col gap-5">
                <div v-for="field in fields" :key="field.key">
                    <label :for="`backstory-${field.key}`" class="block text-sm font-medium text-cthulhu-green-900">
                        {{ field.label }}
                    </label>
                    <p class="text-xs text-cthulhu-green-700">{{ field.hint }}</p>
                    <textarea
                        :id="`backstory-${field.key}`"
                        v-model="form[field.key]"
                        rows="3"
                        class="mt-2"
                        :class="textareaClass"
                    ></textarea>
                    <p v-if="page.props.errors[field.key]" class="mt-1 text-xs text-cthulhu-blood-400">
                        {{ page.props.errors[field.key] }}
                    </p>

                    <div class="mt-1">
                        <!-- Personal description: choose, don't roll -->
                        <HelpDisclosure v-if="field.chooser" label="Choose a look (tap to add)">
                            <div class="flex flex-wrap gap-1">
                                <button
                                    v-for="word in PERSONAL_DESCRIPTION_WORDS"
                                    :key="word"
                                    type="button"
                                    @click="append(field.key, word)"
                                    class="rounded-full bg-parchment-200 px-2 py-0.5 text-[11px] text-cthulhu-green-800 ring-1 ring-parchment-400 hover:bg-parchment-300"
                                >
                                    {{ word }}
                                </button>
                            </div>
                        </HelpDisclosure>

                        <!-- Single 1D10 table -->
                        <HelpDisclosure v-else-if="field.table" label="Roll 1D10 for inspiration">
                            <ol class="flex flex-col gap-1">
                                <li v-for="(entry, index) in field.table" :key="index">
                                    <button
                                        type="button"
                                        @click="append(field.key, entry)"
                                        class="flex w-full gap-2 rounded px-1 py-0.5 text-left hover:bg-parchment-200"
                                    >
                                        <span class="w-5 shrink-0 text-right font-mono font-bold">{{ index + 1 }}</span>
                                        <span>{{ entry }}</span>
                                    </button>
                                </li>
                            </ol>
                            <p class="mt-2 text-[10px] text-cthulhu-green-500">Tap a row to append it as a starting stub.</p>
                        </HelpDisclosure>

                        <!-- Significant people: two tables, roll twice -->
                        <HelpDisclosure v-else-if="field.tables" label="Roll 1D10 twice for inspiration">
                            <div v-for="table in field.tables" :key="table.title" class="mb-3 last:mb-0">
                                <p class="font-semibold">{{ table.title }}</p>
                                <ol class="mt-1 flex flex-col gap-1">
                                    <li v-for="(entry, index) in table.rows" :key="index">
                                        <button
                                            type="button"
                                            @click="append(field.key, entry)"
                                            class="flex w-full gap-2 rounded px-1 py-0.5 text-left hover:bg-parchment-200"
                                        >
                                            <span class="w-5 shrink-0 text-right font-mono font-bold">{{ index + 1 }}</span>
                                            <span>{{ entry }}</span>
                                        </button>
                                    </li>
                                </ol>
                            </div>
                            <p class="text-[10px] text-cthulhu-green-500">Tap a row to append it as a starting stub.</p>
                        </HelpDisclosure>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional extras -->
        <div class="panel p-4 sm:p-6">
            <button
                type="button"
                @click="showMore = !showMore"
                class="flex w-full items-center justify-between text-sm font-semibold text-cthulhu-green-900"
            >
                More (optional)
                <ChevronDownIcon class="size-4 transition-transform" :class="{ 'rotate-180': showMore }" aria-hidden="true" />
            </button>
            <div v-if="showMore" class="mt-4 flex flex-col gap-4">
                <div>
                    <label for="backstory-injuries" class="block text-sm font-medium text-cthulhu-green-900">
                        Injuries &amp; Scars
                    </label>
                    <p class="text-xs text-cthulhu-green-700">
                        Usually earned in play — but write one in if your history implies it.
                    </p>
                    <textarea id="backstory-injuries" v-model="form.injuries_scars" rows="2" class="mt-2" :class="textareaClass"></textarea>
                </div>
                <div>
                    <label for="backstory-phobias" class="block text-sm font-medium text-cthulhu-green-900">
                        Phobias &amp; Manias
                    </label>
                    <textarea id="backstory-phobias" v-model="form.phobias_manias" rows="2" class="mt-2" :class="textareaClass"></textarea>
                </div>
            </div>
        </div>

        <!-- Key connection -->
        <div class="panel p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-cthulhu-green-900">Key connection</h3>
            <p class="mt-1 text-xs leading-5 text-cthulhu-green-700">
                Pick the one backstory entry that gives your investigator's life meaning. It
                can aid Sanity recovery — and the Keeper cannot destroy, kill or take it away
                without first giving you a chance to save it with a dice roll.
            </p>
            <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                <select
                    v-model="keyCategory"
                    class="w-full sm:w-56 rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600"
                >
                    <option value="">Choose category…</option>
                    <option v-for="category in KEY_CONNECTION_CATEGORIES" :key="category" :value="category">
                        {{ category }}
                    </option>
                </select>
                <input
                    type="text"
                    v-model="keyDetail"
                    placeholder="e.g. Uncle Theodore's archaeological artifacts"
                    class="w-full flex-1 rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600"
                />
            </div>
            <p v-if="page.props.errors.key_connection" class="mt-1 text-xs text-cthulhu-blood-400">
                {{ page.props.errors.key_connection }}
            </p>

            <div class="mt-5 flex justify-end">
                <button
                    type="button"
                    @click="submit"
                    :disabled="processing"
                    class="rounded-md bg-cthulhu-green-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-600 disabled:opacity-50"
                >
                    {{ processing ? 'Saving…' : 'Save backstory' }}
                </button>
            </div>
        </div>
    </div>
</template>
