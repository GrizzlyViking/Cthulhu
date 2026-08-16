<script setup>
/**
 * Characteristics by Point Buy: a fixed pool shared among the eight
 * characteristics, no dice at all. Luck sits outside the pool.
 */
import { computed, reactive, ref, watchEffect } from 'vue';
import { MinusIcon, PlusIcon } from '@heroicons/vue/20/solid';
import NumberField from '@/Pages/Components/Wizard/NumberField.vue';
import HelpDisclosure from '@/Pages/Components/Wizard/HelpDisclosure.vue';
import { CHARACTERISTICS, LUCK, POINT_BUY } from '@/Pages/Components/Wizard/wizardData.js';

const finalsModel = defineModel('finals', { type: Object, required: true });
const readyModel = defineModel('ready', { type: Boolean, required: true });

const MAX_LUCK = 90;

const values = reactive(
    Object.fromEntries(CHARACTERISTICS.map((c) => [c.key, null]))
);
const luck = ref(null);

const abbrOf = (key) => CHARACTERISTICS.find((c) => c.key === key)?.abbr ?? key;

/* ------------------------------------------------------------- the pool -- */

const spent = computed(() =>
    CHARACTERISTICS.reduce((sum, c) => sum + (values[c.key] ?? 0), 0)
);

const remaining = computed(() => POINT_BUY.pool - spent.value);

/** Steps by five, and never past the pool or the 15–90 range. */
const bump = (key, delta) => {
    const current = values[key];
    if (current === null && delta < 0) return;

    const target = current === null ? POINT_BUY.min : current + delta * POINT_BUY.step;
    const next = Math.min(POINT_BUY.max, Math.max(POINT_BUY.min, target));
    const cost = next - (current ?? 0);

    if (cost === 0 || cost > remaining.value) return;

    values[key] = next;
};

/* --------------------------------------------------------- validation -- */

const inRange = (key) =>
    values[key] !== null && values[key] >= POINT_BUY.min && values[key] <= POINT_BUY.max;

const luckValid = computed(() => luck.value !== null && luck.value >= 1 && luck.value <= MAX_LUCK);

/**
 * The recommended floor for INT and SIZ is only worth mentioning once the
 * player has actually filled the sheet in — half-typed numbers are not a
 * choice to warn about.
 */
const allEntered = computed(() => CHARACTERISTICS.every((c) => (values[c.key] ?? 0) > 1));

const belowRecommended = computed(() => {
    if (!allEntered.value) return [];

    return POINT_BUY.recommendedFor.filter((key) => values[key] < POINT_BUY.recommendedMinimum);
});

const finals = computed(() => ({ ...values, luck: luck.value }));

const ready = computed(
    () => CHARACTERISTICS.every((c) => inRange(c.key)) && remaining.value === 0 && luckValid.value
);

watchEffect(() => {
    finalsModel.value = finals.value;
    readyModel.value = ready.value;
});
</script>

<template>
    <div class="panel p-4 sm:p-6">
        <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
            Point Buy
        </h2>
        <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
            Share {{ POINT_BUY.pool }} points among the eight characteristics as you wish, each
            one between {{ POINT_BUY.min }} and {{ POINT_BUY.max }}. INT and SIZ are recommended
            to be at least {{ POINT_BUY.recommendedMinimum }}, though they may be lower if your
            Keeper agrees.
        </p>

        <div class="mt-4 flex items-center justify-between rounded-lg bg-cthulhu-green-100/60 px-3 py-2 ring-1 ring-cthulhu-green-300/60">
            <p class="text-xs font-semibold text-cthulhu-green-900">
                {{ spent }} of {{ POINT_BUY.pool }} points spent
            </p>
            <span
                class="rounded-full px-2 py-0.5 text-xs font-bold"
                :class="remaining === 0
                    ? 'bg-cthulhu-green-600 text-white'
                    : remaining < 0
                        ? 'bg-cthulhu-blood-400 text-white'
                        : 'bg-cthulhu-yellow-400 text-cthulhu-green-900'"
            >
                {{ remaining < 0 ? `${-remaining} over` : `${remaining} left` }}
            </span>
        </div>

        <p
            v-if="belowRecommended.length"
            class="mt-2 rounded-lg bg-cthulhu-yellow-200/70 px-3 py-2 text-xs text-cthulhu-green-900 ring-1 ring-cthulhu-yellow-500"
        >
            {{ belowRecommended.map(abbrOf).join(' and ') }}
            {{ belowRecommended.length === 1 ? 'is' : 'are' }} below the recommended minimum of
            {{ POINT_BUY.recommendedMinimum }}. That is allowed, but check with your Keeper first.
        </p>

        <ul class="mt-5 flex flex-col gap-4">
            <li
                v-for="c in CHARACTERISTICS"
                :key="c.key"
                class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60"
            >
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-cthulhu-green-900">
                            {{ c.abbr }}
                            <span class="font-medium text-cthulhu-green-700">— {{ c.name }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            @click="bump(c.key, -1)"
                            class="flex size-7 items-center justify-center rounded-full bg-cthulhu-green-300 text-cthulhu-green-900 hover:bg-cthulhu-green-350"
                            :aria-label="`spend fewer points on ${c.abbr}`"
                        >
                            <MinusIcon class="size-4" />
                        </button>
                        <NumberField
                            v-model="values[c.key]"
                            :min="POINT_BUY.min"
                            :max="POINT_BUY.max"
                            placeholder="—"
                            width-class="w-16"
                        />
                        <button
                            type="button"
                            @click="bump(c.key, 1)"
                            class="flex size-7 items-center justify-center rounded-full bg-cthulhu-green-300 text-cthulhu-green-900 hover:bg-cthulhu-green-350"
                            :aria-label="`spend more points on ${c.abbr}`"
                        >
                            <PlusIcon class="size-4" />
                        </button>
                    </div>
                </div>
                <p
                    v-if="values[c.key] !== null && !inRange(c.key)"
                    class="mt-1 text-xs text-cthulhu-blood-400"
                >
                    Enter a value between {{ POINT_BUY.min }} and {{ POINT_BUY.max }}.
                </p>
                <div class="mt-2">
                    <HelpDisclosure :label="`About ${c.abbr}`">
                        <p>{{ c.description }}</p>
                        <p class="mt-2 font-semibold">What the numbers mean</p>
                        <ul class="mt-1 flex flex-col gap-0.5">
                            <li v-for="[value, meaning] in c.ladder" :key="value" class="flex gap-2">
                                <span class="w-8 shrink-0 text-right font-mono">{{ value }}</span>
                                <span>{{ meaning }}</span>
                            </li>
                        </ul>
                    </HelpDisclosure>
                </div>
            </li>

            <!-- Luck -->
            <li class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-cthulhu-green-900">
                            LUCK <span class="font-medium text-cthulhu-green-700">— Luck</span>
                        </p>
                        <p class="text-xs text-cthulhu-green-700">
                            Not part of the {{ POINT_BUY.pool }} — roll 3D6 × 5, or agree a value
                            with your Keeper.
                        </p>
                    </div>
                    <NumberField
                        v-model="luck"
                        :min="1"
                        :max="MAX_LUCK"
                        placeholder="—"
                        width-class="w-20"
                    />
                </div>
                <p
                    v-if="luck !== null && !luckValid"
                    class="mt-1 text-xs text-cthulhu-blood-400"
                >
                    Enter a value between 1 and {{ MAX_LUCK }}.
                </p>
                <div class="mt-2">
                    <HelpDisclosure label="About Luck">
                        <p>{{ LUCK.description }}</p>
                    </HelpDisclosure>
                </div>
            </li>
        </ul>
    </div>
</template>
