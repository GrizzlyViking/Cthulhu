<script setup>
import { Link } from '@inertiajs/vue3';

/*
 * A row per character, read the way the Keeper reads them mid-scene: current
 * figures, what is wrong with them, what is loaded.
 *
 * The party and the Keeper's own cast are the same table with different ends —
 * the party carries attendance and the last secret roll, the cast carries what
 * it was conjured up as and a way to be rid of it — so the columns in between
 * live here once and the ends come in as slots.
 */
defineProps({
    characters: { type: Array, default: () => [] },
    passiveSkills: { type: Array, default: () => [] },
    /** Whether a row should be dimmed — absent tonight, and out of the rolls. */
    dimmed: { type: Function, default: () => false },
});

/* A figure at zero or below is the one the Keeper should notice first. */
const figureClass = (value) => (Number(value) <= 0 ? 'text-cthulhu-blood-500' : 'text-cthulhu-green-900');
</script>

<template>
    <!-- Wide by nature: it scrolls itself rather than the page. -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[54rem] border-collapse text-sm">
            <thead>
                <tr class="border-b border-parchment-300 text-left">
                    <th v-if="$slots.lead" scope="col" class="px-2 py-2 eyebrow">
                        <slot name="lead-heading" />
                    </th>
                    <th scope="col" class="px-2 py-2 eyebrow">
                        <slot name="name-heading">Investigator</slot>
                    </th>
                    <th scope="col" class="px-2 py-2 eyebrow">HP</th>
                    <th scope="col" class="px-2 py-2 eyebrow">SAN</th>
                    <th scope="col" class="px-2 py-2 eyebrow">MP</th>
                    <th scope="col" class="px-2 py-2 eyebrow">Luck</th>
                    <th v-for="skill in passiveSkills" :key="skill.slug" scope="col" class="px-2 py-2 eyebrow">
                        {{ skill.name }}
                    </th>
                    <th scope="col" class="px-2 py-2 eyebrow">Loaded</th>
                    <th v-if="$slots.trail" scope="col" class="px-2 py-2 eyebrow">
                        <slot name="trail-heading" />
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="character in characters"
                    :key="character.id"
                    class="border-b border-parchment-200 align-top transition"
                    :class="{ 'opacity-40': dimmed(character) }"
                >
                    <td v-if="$slots.lead" class="px-2 py-3">
                        <slot name="lead" :character="character" />
                    </td>

                    <td class="px-2 py-3">
                        <Link
                            :href="route('character.show', { character: character.slug })"
                            class="font-semibold text-cthulhu-green-900 hover:text-cthulhu-green-600"
                        >
                            {{ character.name }}
                        </Link>
                        <span class="block text-xs text-cthulhu-green-500">
                            <slot name="subtitle" :character="character">{{ character.player }}</slot>
                        </span>

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

                    <td v-for="skill in passiveSkills" :key="skill.slug" class="tabular px-2 py-3 text-cthulhu-green-800">
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
                            <span v-if="firearm.reserve" class="text-cthulhu-green-500"> (+{{ firearm.reserve }}) </span>
                        </span>
                    </td>

                    <td v-if="$slots.trail" class="px-2 py-3">
                        <slot name="trail" :character="character" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
