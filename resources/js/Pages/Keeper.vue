<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import { EyeSlashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    /** The campaign being played, or null while the group has none. */
    game: Object,
    party: { type: Array, default: () => [] },
    passiveSkills: { type: Array, default: () => [] },
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

/* A figure at zero or below is the one the Keeper should notice first. */
const figureClass = (value) => (Number(value) <= 0 ? 'text-cthulhu-blood-500' : 'text-cthulhu-green-900');
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

            <section v-else-if="party.length === 0" class="panel p-5 sm:p-6">
                <h2 class="display text-lg text-cthulhu-green-900">Nobody has joined {{ game.name }} yet</h2>
                <p class="field-hint mt-1">
                    Investigators appear here once their players put them in this game, from the Manage sheet panel on
                    their own sheet.
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
                        Last rolled: <span class="font-semibold">{{ rolledSkill }}</span>. The outcomes are in the
                        table below.
                    </p>
                </section>

                <!-- The party -->
                <section class="panel p-4 sm:p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-base font-semibold text-cthulhu-green-900">The party</h2>
                        <button
                            v-if="absent.length"
                            type="button"
                            class="btn-ghost btn-sm"
                            @click="everyoneIsHere"
                        >
                            Everyone's here
                        </button>
                    </div>

                    <!-- Wide by nature: it scrolls itself rather than the page. -->
                    <div class="mt-3 overflow-x-auto">
                        <table class="w-full min-w-[54rem] border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-parchment-300 text-left">
                                    <th scope="col" class="px-2 py-2 eyebrow">Here</th>
                                    <th scope="col" class="px-2 py-2 eyebrow">Investigator</th>
                                    <th scope="col" class="px-2 py-2 eyebrow">HP</th>
                                    <th scope="col" class="px-2 py-2 eyebrow">SAN</th>
                                    <th scope="col" class="px-2 py-2 eyebrow">MP</th>
                                    <th scope="col" class="px-2 py-2 eyebrow">Luck</th>
                                    <th
                                        v-for="skill in passiveSkills"
                                        :key="skill.slug"
                                        scope="col"
                                        class="px-2 py-2 eyebrow"
                                    >
                                        {{ skill.name }}
                                    </th>
                                    <th scope="col" class="px-2 py-2 eyebrow">Loaded</th>
                                    <th scope="col" class="px-2 py-2 eyebrow">Last roll</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="character in party"
                                    :key="character.id"
                                    class="border-b border-parchment-200 align-top transition"
                                    :class="{ 'opacity-40': !isHere(character) }"
                                >
                                    <td class="px-2 py-3">
                                        <input
                                            type="checkbox"
                                            class="size-4 rounded border-parchment-400 bg-parchment-50 text-cthulhu-green-800 focus:ring-cthulhu-green-600"
                                            :checked="isHere(character)"
                                            :aria-label="`${character.name} is at the table`"
                                            @change="toggleAttendance(character)"
                                        />
                                    </td>

                                    <td class="px-2 py-3">
                                        <Link
                                            :href="route('character.show', { character: character.slug })"
                                            class="font-semibold text-cthulhu-green-900 hover:text-cthulhu-green-600"
                                        >
                                            {{ character.name }}
                                        </Link>
                                        <span class="block text-xs text-cthulhu-green-500">{{ character.player }}</span>

                                        <span
                                            v-for="condition in character.conditions"
                                            :key="condition"
                                            class="chip-blood mr-1 mt-1 inline-block"
                                        >
                                            {{ condition }}
                                        </span>
                                    </td>

                                    <td class="tabular px-2 py-3 text-lg font-semibold" :class="figureClass(character.hitPoints)">
                                        {{ character.hitPoints }}
                                    </td>
                                    <td class="tabular px-2 py-3 text-lg font-semibold" :class="figureClass(character.sanity)">
                                        {{ character.sanity }}
                                    </td>
                                    <td class="tabular px-2 py-3 text-cthulhu-green-800">{{ character.magicPoints }}</td>
                                    <td class="tabular px-2 py-3 text-cthulhu-green-800">{{ character.luck }}</td>

                                    <td
                                        v-for="skill in passiveSkills"
                                        :key="skill.slug"
                                        class="tabular px-2 py-3 text-cthulhu-green-800"
                                    >
                                        {{ character.skills[skill.slug] }}
                                    </td>

                                    <td class="px-2 py-3">
                                        <span v-if="!character.firearms.length" class="text-cthulhu-green-500">—</span>
                                        <span
                                            v-for="firearm in character.firearms"
                                            :key="firearm.name"
                                            class="block whitespace-nowrap text-xs text-cthulhu-green-700"
                                        >
                                            {{ firearm.name }}
                                            <span class="tabular font-semibold">{{ firearm.ammo }}</span>
                                            <span v-if="firearm.reserve" class="text-cthulhu-green-500">
                                                (+{{ firearm.reserve }})
                                            </span>
                                        </span>
                                    </td>

                                    <td class="px-2 py-3">
                                        <template v-if="resultFor(character)">
                                            <span class="tabular block whitespace-nowrap text-xs text-cthulhu-green-500">
                                                {{ resultFor(character).roll }} vs {{ resultFor(character).value }}
                                            </span>
                                            <span :class="outcomeClass(resultFor(character))" class="mt-1 inline-block">
                                                {{ resultFor(character).outcome }}
                                            </span>
                                        </template>
                                        <span v-else class="text-cthulhu-green-500">—</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
