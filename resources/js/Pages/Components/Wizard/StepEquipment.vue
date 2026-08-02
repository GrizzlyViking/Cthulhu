<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import {
    CHARACTERISTICS,
    eraLabel,
    formatMoney,
} from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    draft: { type: Object, required: true },
    era: { type: String, required: true },
    wealth: { type: Object, default: null },
});

const emit = defineEmits(['advance']);

const page = usePage();
const savingGear = ref(false);
const completing = ref(false);

const gear = ref(props.draft.backstory?.gear ?? '');

const gearDirty = computed(
    () => gear.value !== (props.draft.backstory?.gear ?? '')
);

const saveGear = (onSuccess = null) => {
    savingGear.value = true;
    router.put(
        route('character.wizard.backstory', { character: props.draft.slug }),
        { gear: gear.value || null },
        {
            preserveScroll: true,
            onSuccess: () => onSuccess?.(),
            onFinish: () => (savingGear.value = false),
        }
    );
};

const complete = () => {
    const finish = () => {
        completing.value = true;
        router.put(
            route('character.wizard.complete', { character: props.draft.slug }),
            {},
            { onFinish: () => (completing.value = false) }
        );
    };

    if (gearDirty.value) {
        saveGear(finish);
    } else {
        finish();
    }
};

const topSkills = computed(() =>
    [...(props.draft.skills ?? [])]
        .filter((skill) => (skill.pivot?.value ?? 0) > 0)
        .sort((a, b) => (b.pivot?.value ?? 0) - (a.pivot?.value ?? 0))
        .slice(0, 6)
);

const creditRating = computed(
    () =>
        (props.draft.skills ?? []).find((skill) => skill.slug === 'credit_rating')
            ?.pivot?.value ?? 0
);
</script>

<template>
    <div class="flex flex-col gap-3">
        <!-- Wealth -->
        <div class="panel p-4 sm:p-6">
            <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
                Equipment &amp; wealth — {{ eraLabel(era) }}
            </h2>

            <template v-if="wealth">
                <div class="mt-3 rounded-lg bg-parchment-50 p-4 ring-1 ring-parchment-300">
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-lg font-bold text-cthulhu-green-900">
                            {{ wealth.living_standard }}
                        </p>
                        <p class="text-xs text-cthulhu-green-700">
                            Credit Rating {{ creditRating }}
                        </p>
                    </div>
                    <p class="mt-1 text-xs leading-5 text-cthulhu-green-700">
                        {{ wealth.description }}
                    </p>
                    <dl class="mt-3 grid grid-cols-3 gap-2 text-center">
                        <div>
                            <dt class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">Cash</dt>
                            <dd class="text-sm font-bold text-cthulhu-green-900">
                                {{ formatMoney(wealth.cash, era) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">Assets</dt>
                            <dd class="text-sm font-bold text-cthulhu-green-900">
                                {{ formatMoney(wealth.assets, era) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">Spending Level</dt>
                            <dd class="text-sm font-bold text-cthulhu-green-900">
                                {{ formatMoney(wealth.spending_level, era) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </template>
            <p v-else class="mt-3 text-xs text-cthulhu-blood-400">
                Allocate points to Credit Rating in the Skills step to see your wealth.
            </p>

            <p class="mt-3 text-xs leading-5 text-cthulhu-green-700">
                Items that fit your living standard are simply owned — no bookkeeping needed.
                List only notable possessions: the doctor's bag, the service revolver, the
                strange silver ball you dug up in your garden.
            </p>

            <label for="wizard-gear" class="mt-4 block text-sm font-medium text-cthulhu-green-900">
                Gear &amp; notable possessions
            </label>
            <textarea
                id="wizard-gear"
                v-model="gear"
                rows="4"
                placeholder="A notebook, a pen, and a lucky penny…"
                class="mt-2 block w-full rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600"
            ></textarea>
            <p v-if="page.props.errors.gear" class="mt-1 text-xs text-cthulhu-blood-400">{{ page.props.errors.gear }}</p>
            <div class="mt-2 flex justify-end">
                <button
                    type="button"
                    @click="saveGear()"
                    :disabled="savingGear || !gearDirty"
                    class="rounded-md bg-cthulhu-green-400 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-cthulhu-green-350 disabled:opacity-40"
                >
                    {{ savingGear ? 'Saving…' : 'Save gear' }}
                </button>
            </div>

            <p class="mt-2 text-xs text-cthulhu-green-700">
                Weapons can be equipped from the character sheet once your investigator is
                complete.
            </p>
        </div>

        <!-- Summary recap -->
        <div class="panel p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-cthulhu-green-900">
                {{ draft.name }}
                <span class="font-normal text-cthulhu-green-700">
                    — {{ draft.occupation }}, {{ draft.age }}, {{ draft.residence }}
                </span>
            </h3>

            <div class="mt-3 grid grid-cols-4 gap-2 sm:grid-cols-8">
                <div
                    v-for="c in CHARACTERISTICS"
                    :key="c.key"
                    class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300"
                >
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">{{ c.abbr }}</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft[c.key] }}</p>
                </div>
            </div>

            <div class="mt-2 grid grid-cols-3 gap-2 sm:grid-cols-6">
                <div class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300">
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">HP</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft.hit_points }}</p>
                </div>
                <div class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300">
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">SAN</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft.sanity }}</p>
                </div>
                <div class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300">
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">MP</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft.magic_points }}</p>
                </div>
                <div class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300">
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">Luck</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft.luck }}</p>
                </div>
                <div class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300">
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">Move</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft.move_rate }}</p>
                </div>
                <div class="rounded-lg bg-parchment-50 p-2 text-center ring-1 ring-parchment-300">
                    <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">DB</p>
                    <p class="text-sm font-bold text-cthulhu-green-900">{{ draft.damage_bonus }}</p>
                </div>
            </div>

            <div v-if="topSkills.length" class="mt-3">
                <p class="text-[10px] font-semibold uppercase tracking-wide text-cthulhu-green-500">Top skills</p>
                <ul class="mt-1 flex flex-wrap gap-1">
                    <li
                        v-for="skill in topSkills"
                        :key="skill.slug"
                        class="rounded-full bg-parchment-200 px-2 py-0.5 text-[11px] text-cthulhu-green-800 ring-1 ring-parchment-400"
                    >
                        {{ skill.display_name }} {{ skill.pivot.value }}%
                    </li>
                </ul>
            </div>

            <p v-if="draft.backstory?.key_connection" class="mt-3 text-xs text-cthulhu-green-700">
                <span class="font-semibold">★ Key connection:</span>
                {{ draft.backstory.key_connection }}
            </p>

            <div class="mt-5 flex justify-end">
                <button
                    type="button"
                    @click="complete"
                    :disabled="completing || savingGear || draft.wizard_step < 5"
                    class="w-full sm:w-auto rounded-md bg-cthulhu-green-800 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-cthulhu-green-600 disabled:opacity-40"
                >
                    {{ completing ? 'Completing…' : 'Complete investigator' }}
                </button>
            </div>
            <p v-if="draft.wizard_step < 5" class="mt-2 text-right text-xs text-cthulhu-green-700">
                Finish the previous steps before completing.
            </p>
        </div>
    </div>
</template>
