<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useAdjustAttribute } from '@/Pages/Composables/useAdjustAttribute.js';
import { BookOpenIcon, UserCircleIcon } from '@heroicons/vue/24/solid/index.js';

const { updateAttribute } = useAdjustAttribute('character');

const prop = defineProps({ character: Object, editable: Boolean });

const avatarImg = computed(() => {
    if (prop.character.avatar) {
        return '/storage/' + prop.character.avatar;
    }
    return '/images/cthulhu_man_reading.jpeg';
});

/**
 * @type {import('vue').ComputedRef<{key: string, label: string, type: string}[]>}
 */
const identity = computed(() => [
    { key: 'age', label: 'Age', type: 'number' },
    // Gender is an enum with a check constraint behind it, so it is picked
    // rather than typed — anything else is refused by the database.
    { key: 'gender', label: 'Gender', type: 'text', options: ['Male', 'Female', 'Other'] },
]);

/**
 * @type {import('vue').ComputedRef<{key: string, label: string, type: string}[]>}
 */
const background = computed(() => [
    { key: 'occupation', label: 'Occupation', type: 'text' },
    { key: 'residence', label: 'Residence', type: 'text' },
    { key: 'birthplace', label: 'Birthplace', type: 'text' },
]);

/**
 * Renaming re-slugs the character, so this is a full Inertia visit rather than an
 * axios call — the redirect carries the page to the new URL.
 */
const renameCharacter = (event) => {
    const name = event.target.value.trim();

    if (! prop.editable || name === '' || name === prop.character.name) {
        event.target.value = prop.character.name;

        return;
    }

    router.put(route('character.rename', { character: prop.character.slug }), {
        value: name,
    }, { preserveScroll: true });
};
</script>

<template>
    <section class="relative isolate overflow-hidden rounded-2xl shadow-raised ring-1 ring-cthulhu-green-900/40">
        <img
            :src="avatarImg"
            alt=""
            class="absolute inset-0 -z-20 size-full object-cover object-center"
        />
        <!-- Scrim keeps the type legible whatever portrait the player uploads. -->
        <div
            class="absolute inset-0 -z-10 bg-gradient-to-t from-cthulhu-green-950 via-cthulhu-green-950/85 to-cthulhu-green-950/40"
            aria-hidden="true"
        ></div>

        <div class="flex flex-col gap-8 p-6 pt-24 sm:p-8 sm:pt-32 lg:pt-40">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <!-- The name is the widest thing on the sheet: give it the whole row, and the
                     rest of the row's slack once the actions fit alongside it. -->
                <div class="w-full min-w-0 grow sm:w-auto">
                    <p class="eyebrow-on-dark">Investigator</p>
                    <input
                        :value="prop.character.name"
                        aria-label="Character name"
                        class="display mt-1 w-full min-w-0 border-0 bg-transparent p-0 text-3xl text-parchment-100 sm:text-4xl lg:text-5xl"
                        :class="prop.editable ? 'field-inline' : 'ring-0 focus:ring-0'"
                        :disabled="!prop.editable"
                        @focusout="renameCharacter"
                    />
                    <!-- The Keeper's own cast has no player, and only ever
                         reaches this sheet in the Keeper's own hands. -->
                    <p class="mt-2 text-sm text-cthulhu-green-200">
                        <template v-if="prop.character.player">
                            Played by {{ prop.character.player.name }}
                        </template>
                        <template v-else> Nobody's investigator — one of the Keeper's own. </template>
                    </p>
                </div>

                <slot name="actions" />
            </div>

            <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-cthulhu-green-900/70 p-5 ring-1 ring-inset ring-parchment-100/10 backdrop-blur-sm">
                    <div class="flex items-center gap-2">
                        <UserCircleIcon class="size-5 shrink-0 text-cthulhu-yellow-400" aria-hidden="true" />
                        <h3 class="text-sm font-semibold text-parchment-100">Character</h3>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div v-for="row in identity" :key="row.key" class="grid grid-cols-[7rem_1fr] items-center gap-2">
                            <dt class="text-sm text-cthulhu-green-200">{{ row.label }}</dt>
                            <dd>
                                <select
                                    v-if="row.options"
                                    v-model="prop.character[row.key]"
                                    :aria-label="row.label"
                                    class="field-inline w-full text-sm text-parchment-100"
                                    :disabled="!prop.editable"
                                    @change="updateAttribute(row.key, $event)"
                                >
                                    <option v-for="option in row.options" :key="option" :value="option" class="text-cthulhu-green-900">
                                        {{ option }}
                                    </option>
                                </select>
                                <input
                                    v-else
                                    v-model="prop.character[row.key]"
                                    :type="row.type"
                                    :aria-label="row.label"
                                    class="field-inline text-sm text-parchment-100"
                                    :disabled="!prop.editable"
                                    @input="updateAttribute(row.key, $event)"
                                />
                            </dd>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-cthulhu-green-900/70 p-5 ring-1 ring-inset ring-parchment-100/10 backdrop-blur-sm">
                    <div class="flex items-center gap-2">
                        <BookOpenIcon class="size-5 shrink-0 text-cthulhu-yellow-400" aria-hidden="true" />
                        <h3 class="text-sm font-semibold text-parchment-100">Background</h3>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div v-for="row in background" :key="row.key" class="grid grid-cols-[7rem_1fr] items-center gap-2">
                            <dt class="text-sm text-cthulhu-green-200">{{ row.label }}</dt>
                            <dd>
                                <input
                                    v-model="prop.character[row.key]"
                                    :type="row.type"
                                    :aria-label="row.label"
                                    class="field-inline text-sm text-parchment-100"
                                    :disabled="!prop.editable"
                                    @input="updateAttribute(row.key, $event)"
                                />
                            </dd>
                        </div>
                    </div>
                </div>
            </dl>
        </div>
    </section>
</template>
