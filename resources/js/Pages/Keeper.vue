<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PartyTable from '@/Pages/Components/Keeper/PartyTable.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import {
    EyeSlashIcon,
    SparklesIcon,
    TrashIcon,
} from '@heroicons/vue/20/solid';

const props = defineProps({
    /** The campaign being played, or null while the group has none. */
    game: Object,
    party: { type: Array, default: () => [] },
    passiveSkills: { type: Array, default: () => [] },
    /** This Keeper's own non-player characters in that campaign. */
    cast: { type: Array, default: () => [] },
    archetypes: { type: Array, default: () => [] },
    occupations: { type: Array, default: () => [] },
});

/*
 * Who is at the table tonight. Seven players are on the books and it is rare
 * for all seven to turn up, so absentees are dimmed and left out of the rolls.
 * It is the Keeper's own view of his own evening, so it lives in his browser —
 * nothing to save, nothing anyone else sees.
 */
const storageKey = computed(() => `keeper.absent.${props.game?.id ?? 'none'}`);

const absent = ref([]);

onMounted(() => {
    try {
        const stored = JSON.parse(window.localStorage.getItem(storageKey.value) ?? '[]');
        absent.value = Array.isArray(stored) ? stored : [];
    } catch {
        // A corrupted entry is not worth a broken screen: start with everyone here.
        absent.value = [];
    }
});

const rememberAttendance = () => {
    window.localStorage.setItem(storageKey.value, JSON.stringify(absent.value));
};

const isHere = (character) => !absent.value.includes(character.id);

const toggleAttendance = (character) => {
    const index = absent.value.indexOf(character.id);

    if (index === -1) {
        absent.value.push(character.id);
    } else {
        absent.value.splice(index, 1);
    }

    rememberAttendance();
};

const everyoneIsHere = () => {
    absent.value = [];
    rememberAttendance();
};

const present = computed(() => props.party.filter(isHere));

/* The last secret roll. A new one replaces it — nothing is kept. */
const rolledSkill = ref(null);
const rolling = ref(false);
const results = ref({});

const rollFor = (skill) => {
    if (present.value.length === 0 || rolling.value) {
        return;
    }

    rolling.value = true;

    axios
        .post(route('keeper.roll'), {
            skill_slug: skill.slug,
            characters: present.value.map((character) => character.id),
        })
        .then(({ data }) => {
            rolledSkill.value = data.skill;
            results.value = Object.fromEntries(data.results.map((result) => [result.character_id, result]));
        })
        .finally(() => {
            rolling.value = false;
        });
};

/** Nobody keeps a result they were not rolled for. */
const resultFor = (character) => (isHere(character) ? results.value[character.id] : undefined);

const outcomeClass = (result) => (result.success ? 'chip-brass' : 'chip-blood');

/*
 * Conjuring one up: press what they are, say what they do for a living if it
 * matters, create. Everything else — characteristics, skills, a weapon with
 * rounds in it, the three things in their pockets — is rolled on the server.
 */
const archetype = ref(props.archetypes[0]?.value ?? null);
const occupationId = ref('');
const creating = ref(false);

const chosenArchetype = computed(() => props.archetypes.find((option) => option.value === archetype.value));

const conjure = () => {
    if (!archetype.value || creating.value) {
        return;
    }

    router.post(
        route('keeper.npcs.store'),
        { archetype: archetype.value, occupation_id: occupationId.value === '' ? null : occupationId.value },
        {
            preserveScroll: true,
            onStart: () => (creating.value = true),
            onFinish: () => (creating.value = false),
        },
    );
};

/* Deleting is for good, so it asks once — inline, without a dialog in the way. */
const confirmingId = ref(null);

const dismiss = (character) => {
    if (confirmingId.value !== character.id) {
        confirmingId.value = character.id;

        return;
    }

    router.delete(route('keeper.npcs.destroy', { character: character.slug }), {
        preserveScroll: true,
        onFinish: () => (confirmingId.value = null),
    });
};
</script>

<template>
    <Head title="Keeper" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Keeper</h1>
            <span v-if="game" class="chip-brass">{{ game.name }} · {{ game.era }}</span>
        </template>

        <div class="page">
            <!-- No campaign, or nobody in it -->
            <section v-if="!game" class="panel p-5 sm:p-6">
                <h2 class="display text-lg text-cthulhu-green-900">No game is being played</h2>
                <p class="field-hint mt-1">
                    The Keeper's screen shows the party of the campaign your group is playing. An admin can start one
                    on the Group page.
                </p>
            </section>

            <template v-else>
                <!-- The result of the last thing conjured up or dismissed comes
                     from AuthenticatedLayout. -->
                <section v-if="party.length === 0" class="panel p-5 sm:p-6">
                    <h2 class="display text-lg text-cthulhu-green-900">Nobody has joined {{ game.name }} yet</h2>
                    <p class="field-hint mt-1">
                        Investigators appear here once their players put them in this game, from the Manage sheet panel
                        on their own sheet.
                    </p>
                </section>

                <template v-else>
                    <!-- Secret rolls -->
                    <section class="panel p-4 sm:p-5">
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h2 class="text-base font-semibold text-cthulhu-green-900">Secret roll</h2>
                            <p class="field-hint">
                                Rolled against everyone at the table without telling them. Only the latest roll is kept.
                            </p>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <button
                                v-for="skill in passiveSkills"
                                :key="skill.slug"
                                type="button"
                                class="btn-primary"
                                :disabled="rolling || present.length === 0"
                                @click="rollFor(skill)"
                            >
                                <EyeSlashIcon class="size-4" aria-hidden="true" />
                                {{ skill.name }}
                            </button>

                            <span v-if="present.length === 0" class="text-sm text-cthulhu-blood-500">
                                Nobody is marked as here.
                            </span>
                            <span v-else class="text-sm text-cthulhu-green-500">
                                for {{ present.length }} of {{ party.length }} at the table
                            </span>
                        </div>

                        <p v-if="rolledSkill" class="mt-3 text-sm text-cthulhu-green-700">
                            Last rolled: <span class="font-semibold">{{ rolledSkill }}</span
                            >. The outcomes are in the table below.
                        </p>
                    </section>

                    <!-- The party -->
                    <section class="panel p-4 sm:p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-base font-semibold text-cthulhu-green-900">The party</h2>
                            <button v-if="absent.length" type="button" class="btn-ghost btn-sm" @click="everyoneIsHere">
                                Everyone's here
                            </button>
                        </div>

                        <PartyTable class="mt-3" :characters="party" :passive-skills="passiveSkills" :dimmed="(character) => !isHere(character)">
                            <template #lead-heading>Here</template>

                            <template #lead="{ character }">
                                <input
                                    type="checkbox"
                                    class="size-4 rounded border-parchment-400 bg-parchment-50 text-cthulhu-green-800 focus:ring-cthulhu-green-600"
                                    :checked="isHere(character)"
                                    :aria-label="`${character.name} is at the table`"
                                    @change="toggleAttendance(character)"
                                />
                            </template>

                            <template #trail-heading>Last roll</template>

                            <template #trail="{ character }">
                                <template v-if="resultFor(character)">
                                    <span class="tabular block whitespace-nowrap text-xs text-cthulhu-green-500">
                                        {{ resultFor(character).roll }} vs {{ resultFor(character).value }}
                                    </span>
                                    <span :class="outcomeClass(resultFor(character))" class="mt-1 inline-block">
                                        {{ resultFor(character).outcome }}
                                    </span>
                                </template>
                                <span v-else class="text-cthulhu-green-500">—</span>
                            </template>
                        </PartyTable>
                    </section>
                </template>

                <!-- The Keeper's own cast -->
                <section class="panel p-4 sm:p-5">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <h2 class="text-base font-semibold text-cthulhu-green-900">Your cast</h2>
                        <p class="field-hint">Conjured up whole, in this game, and visible to nobody but you.</p>
                    </div>

                    <div class="mt-3 flex flex-wrap items-end gap-2">
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="option in archetypes"
                                :key="option.value"
                                type="button"
                                :class="option.value === archetype ? 'btn-primary' : 'btn-secondary'"
                                :title="option.description"
                                @click="archetype = option.value"
                            >
                                {{ option.label }}
                            </button>
                        </div>

                        <div>
                            <label for="npc-occupation" class="sr-only">Occupation</label>
                            <select id="npc-occupation" v-model="occupationId" class="field w-auto">
                                <option value="">Whatever suits them</option>
                                <option v-for="occupation in occupations" :key="occupation.id" :value="occupation.id">
                                    {{ occupation.name }}
                                </option>
                            </select>
                        </div>

                        <button type="button" class="btn-primary" :disabled="creating || !archetype" @click="conjure">
                            <SparklesIcon class="size-4" aria-hidden="true" />
                            Create
                        </button>
                    </div>

                    <p v-if="chosenArchetype" class="field-hint mt-2">{{ chosenArchetype.description }}</p>

                    <p v-if="cast.length === 0" class="field-hint mt-4">
                        Nobody yet. Whoever you make lands in {{ game.name }} with their skills rolled, something to
                        fight with and the essentials in their pockets — and can be deleted the moment the scene is
                        over.
                    </p>

                    <PartyTable v-else class="mt-3" :characters="cast" :passive-skills="passiveSkills">
                        <template #name-heading>Character</template>

                        <template #subtitle="{ character }">
                            {{ [character.archetype, character.occupation].filter(Boolean).join(' · ') }}
                        </template>

                        <template #trail-heading>Done with</template>

                        <template #trail="{ character }">
                            <button
                                type="button"
                                class="btn-sm whitespace-nowrap"
                                :class="confirmingId === character.id ? 'btn-danger' : 'btn-ghost'"
                                @click="dismiss(character)"
                            >
                                <TrashIcon class="size-4" aria-hidden="true" />
                                {{ confirmingId === character.id ? 'For good?' : 'Delete' }}
                            </button>
                        </template>
                    </PartyTable>
                </section>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
