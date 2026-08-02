<script setup>
import { computed, reactive, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { MinusIcon, PlusIcon } from '@heroicons/vue/20/solid';
import NumberField from '@/Pages/Components/Wizard/NumberField.vue';
import HelpDisclosure from '@/Pages/Components/Wizard/HelpDisclosure.vue';
import {
    CHARACTERISTICS,
    LUCK,
    ageModifiers,
    baseMoveRate,
    damageBonusAndBuild,
    fifth,
    half,
    hitPoints,
} from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    draft: { type: Object, required: true },
});

const emit = defineEmits(['advance']);

const page = usePage();
const processing = ref(false);

const mods = computed(() => ageModifiers(props.draft.age));

/* ---------------------------------------------------------- raw rolls -- */

const raw = reactive(
    Object.fromEntries(CHARACTERISTICS.map((c) => [c.key, null]))
);
const luckRaw = ref(null);
const luckRawSecond = ref(null);

const rawValid = (c) =>
    raw[c.key] !== null && raw[c.key] >= c.min && raw[c.key] <= c.max;

/* ------------------------------------------------- deduction allocator -- */

const deductions = reactive({});

const abbrOf = (key) => CHARACTERISTICS.find((c) => c.key === key)?.abbr ?? key;

const deductionSpent = computed(() => {
    if (!mods.value?.deduction) return 0;
    return mods.value.deduction.targets.reduce(
        (sum, key) => sum + (deductions[key] ?? 0),
        0
    );
});

const deductionRemaining = computed(() =>
    mods.value?.deduction ? mods.value.deduction.amount - deductionSpent.value : 0
);

const bump = (key, delta) => {
    const current = deductions[key] ?? 0;
    const next = current + delta;
    if (next < 0) return;
    if (delta > 0 && deductionRemaining.value <= 0) return;
    // Never push the final value below 1.
    const base = (raw[key] ?? 0) * 5;
    if (delta > 0 && base > 0 && base - next < 1) return;
    deductions[key] = next;
};

/* ------------------------------------------------ EDU improvement checks -- */

const eduChecks = reactive([]);
const ensureChecks = () => {
    const wanted = mods.value?.eduChecks ?? 0;
    while (eduChecks.length < wanted) eduChecks.push({ d100: null, d10: null });
    eduChecks.length = wanted;
};
ensureChecks();

const eduBase = computed(() => {
    if (raw.education === null) return null;
    return raw.education * 5 - (mods.value?.eduPenalty ?? 0);
});

/**
 * @returns {Array<{before:number, d100:number|null, success:boolean, after:number}>}
 */
const eduCheckStates = computed(() => {
    let running = eduBase.value ?? 0;
    return eduChecks.map((check) => {
        const before = running;
        const success = check.d100 !== null && check.d100 > before;
        const gain = success ? check.d10 ?? 0 : 0;
        const after = Math.min(99, before + gain);
        running = after;
        return { before, d100: check.d100, success, after };
    });
});

/* ----------------------------------------------------------- finals -- */

const finals = computed(() => {
    const value = (key) => (raw[key] === null ? null : raw[key] * 5);

    const minusDeduction = (key) => {
        const v = value(key);
        return v === null ? null : v - (deductions[key] ?? 0);
    };

    let education = eduBase.value;
    if (education !== null && eduChecks.length > 0) {
        const states = eduCheckStates.value;
        education = states.length ? states[states.length - 1].after : education;
    }

    let luck = null;
    if (luckRaw.value !== null) {
        luck = mods.value?.luckTwice
            ? Math.max(luckRaw.value, luckRawSecond.value ?? 0) * 5
            : luckRaw.value * 5;
    }

    return {
        strength: minusDeduction('strength'),
        constitution: minusDeduction('constitution'),
        size: minusDeduction('size'),
        dexterity: minusDeduction('dexterity'),
        appearance:
            value('appearance') === null
                ? null
                : Math.max(1, value('appearance') - (mods.value?.appPenalty ?? 0)),
        intelligence: value('intelligence'),
        power: value('power'),
        education,
        luck,
    };
});

/* --------------------------------------------------------- validation -- */

const luckValid = computed(() => {
    const first = luckRaw.value !== null && luckRaw.value >= 3 && luckRaw.value <= 18;
    if (!mods.value?.luckTwice) return first;
    return (
        first &&
        luckRawSecond.value !== null &&
        luckRawSecond.value >= 3 &&
        luckRawSecond.value <= 18
    );
});

const checksValid = computed(() =>
    eduChecks.every((check, index) => {
        if (check.d100 === null || check.d100 < 1 || check.d100 > 100) return false;
        const state = eduCheckStates.value[index];
        if (state.success && (check.d10 === null || check.d10 < 1 || check.d10 > 10)) return false;
        return true;
    })
);

const ready = computed(() => {
    if (!CHARACTERISTICS.every(rawValid)) return false;
    if (!luckValid.value) return false;
    if (mods.value?.deduction && deductionRemaining.value !== 0) return false;
    if (!checksValid.value) return false;
    return Object.values(finals.value).every((v) => v !== null && v >= 1 && v <= 99);
});

/* ------------------------------------------------------------ derived -- */

const derived = computed(() => {
    const f = finals.value;
    const complete = [f.strength, f.constitution, f.size, f.dexterity, f.power].every(
        (v) => v !== null
    );
    if (!complete) return null;
    const { damageBonus, build } = damageBonusAndBuild(f.strength, f.size);
    return {
        hitPoints: hitPoints(f.constitution, f.size),
        sanity: f.power,
        magicPoints: fifth(f.power),
        dodge: half(f.dexterity),
        moveBase: baseMoveRate(f.strength, f.dexterity, f.size),
        moveDeduction: mods.value?.moveDeduction ?? 0,
        damageBonus,
        build,
    };
});

/* ------------------------------------------------------------- submit -- */

const alreadySaved = computed(() => props.draft.wizard_step >= 2);

const submit = () => {
    processing.value = true;
    router.put(
        route('character.wizard.characteristics', { character: props.draft.slug }),
        finals.value,
        {
            preserveScroll: true,
            onSuccess: () => emit('advance'),
            onFinish: () => (processing.value = false),
        }
    );
};

const savedSummary = computed(() =>
    CHARACTERISTICS.map((c) => `${c.abbr} ${props.draft[c.key]}`).join(' · ') +
    ` · LUCK ${props.draft.luck}`
);
</script>

<template>
    <div class="flex flex-col gap-3">
        <div class="panel p-4 sm:p-6">
            <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
                Roll the dice
            </h2>
            <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
                Grab your real dice. For each characteristic, roll as instructed and type in the
                raw total — the app multiplies by five and applies your age modifiers for you.
            </p>

            <div v-if="alreadySaved" class="mt-3 rounded-lg bg-cthulhu-green-100 p-3 text-xs text-cthulhu-green-700 ring-1 ring-cthulhu-green-300">
                <p class="font-semibold">Saved characteristics</p>
                <p class="mt-1">{{ savedSummary }}</p>
                <p class="mt-1">Re-enter rolls below only if you want to replace them.</p>
                <button
                    type="button"
                    @click="emit('advance')"
                    class="mt-2 rounded-md bg-cthulhu-green-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-cthulhu-green-600"
                >
                    Keep saved values →
                </button>
            </div>

            <!-- Characteristic inputs -->
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
                            <p class="text-xs text-cthulhu-green-700">{{ c.rollHelp }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <NumberField v-model="raw[c.key]" :min="c.min" :max="c.max" placeholder="—" width-class="w-16" />
                            <div class="w-14 text-center">
                                <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">×5</p>
                                <p class="text-lg font-bold text-cthulhu-green-800">
                                    {{ raw[c.key] !== null ? raw[c.key] * 5 : '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <p
                        v-if="raw[c.key] !== null && !rawValid(c)"
                        class="mt-1 text-xs text-cthulhu-blood-400"
                    >
                        Enter a total between {{ c.min }} and {{ c.max }}.
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
                                {{ mods?.luckTwice ? LUCK.rollHelpTeen : LUCK.rollHelp }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <NumberField v-model="luckRaw" :min="3" :max="18" placeholder="—" width-class="w-16" />
                            <NumberField
                                v-if="mods?.luckTwice"
                                v-model="luckRawSecond"
                                :min="3"
                                :max="18"
                                placeholder="—"
                                width-class="w-16"
                            />
                            <div class="w-14 text-center">
                                <p class="text-[10px] uppercase tracking-wide text-cthulhu-green-500">×5</p>
                                <p class="text-lg font-bold text-cthulhu-green-800">
                                    {{ finals.luck ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <HelpDisclosure label="About Luck">
                            <p>{{ LUCK.description }}</p>
                        </HelpDisclosure>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Age modifiers -->
        <div v-if="mods" class="panel p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-cthulhu-green-900">
                Age modifiers — {{ mods.bracket }} (age {{ draft.age }})
            </h3>
            <p class="mt-1 text-xs text-cthulhu-green-700">{{ mods.summary }}</p>

            <!-- Deduction allocator -->
            <div v-if="mods.deduction" class="mt-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-cthulhu-green-900">
                        Distribute {{ mods.deduction.amount }} points of deductions among
                        {{ mods.deduction.targets.map(abbrOf).join(', ') }}
                    </p>
                    <span
                        class="rounded-full px-2 py-0.5 text-xs font-bold"
                        :class="deductionRemaining === 0
                            ? 'bg-cthulhu-green-600 text-white'
                            : 'bg-cthulhu-yellow-400 text-cthulhu-green-900'"
                    >
                        {{ deductionRemaining }} left
                    </span>
                </div>
                <ul class="mt-2 flex flex-col gap-2">
                    <li
                        v-for="key in mods.deduction.targets"
                        :key="key"
                        class="flex items-center justify-between rounded-lg bg-cthulhu-green-100/60 px-3 py-2 ring-1 ring-cthulhu-green-300/60"
                    >
                        <span class="text-sm font-medium text-cthulhu-green-900">{{ abbrOf(key) }}</span>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="bump(key, -1)"
                                class="flex size-7 items-center justify-center rounded-full bg-cthulhu-green-300 text-cthulhu-green-900 hover:bg-cthulhu-green-350"
                                aria-label="decrease deduction"
                            >
                                <MinusIcon class="size-4" />
                            </button>
                            <span class="w-8 text-center text-sm font-bold text-cthulhu-green-900">
                                −{{ deductions[key] ?? 0 }}
                            </span>
                            <button
                                type="button"
                                @click="bump(key, 1)"
                                class="flex size-7 items-center justify-center rounded-full bg-cthulhu-green-300 text-cthulhu-green-900 hover:bg-cthulhu-green-350"
                                aria-label="increase deduction"
                            >
                                <PlusIcon class="size-4" />
                            </button>
                            <span class="w-12 text-right text-xs text-cthulhu-green-700">
                                → {{ finals[key] ?? '—' }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- APP penalty note -->
            <p v-if="mods.appPenalty" class="mt-3 text-xs text-cthulhu-green-700">
                APP is reduced by {{ mods.appPenalty }} automatically
                ({{ raw.appearance !== null ? `${raw.appearance * 5} → ${finals.appearance}` : 'enter your APP roll above' }}).
            </p>
            <p v-if="mods.eduPenalty" class="mt-3 text-xs text-cthulhu-green-700">
                EDU is reduced by {{ mods.eduPenalty }} automatically
                ({{ raw.education !== null ? `${raw.education * 5} → ${finals.education}` : 'enter your EDU roll above' }}).
            </p>

            <!-- EDU improvement checks -->
            <div v-if="mods.eduChecks > 0" class="mt-4">
                <p class="text-xs font-semibold text-cthulhu-green-900">
                    EDU improvement {{ mods.eduChecks === 1 ? 'check' : 'checks' }}
                </p>
                <p class="mt-1 text-xs text-cthulhu-green-700">
                    For each check: roll percentile dice (D100) and enter the result. If it is
                    higher than your current EDU, the years have taught you something.
                </p>
                <p v-if="raw.education === null" class="mt-2 text-xs text-cthulhu-blood-400">
                    Enter your EDU roll above first — the checks apply to the running EDU value.
                </p>
                <ol v-else class="mt-2 flex flex-col gap-2">
                    <li
                        v-for="(check, index) in eduChecks"
                        :key="index"
                        class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60"
                    >
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold text-cthulhu-green-900">
                                Check {{ index + 1 }} — EDU {{ eduCheckStates[index].before }}:
                            </span>
                            <span class="text-xs text-cthulhu-green-700">Roll D100 and enter the result</span>
                            <NumberField v-model="check.d100" :min="1" :max="100" placeholder="D100" width-class="w-20" />
                        </div>
                        <div v-if="check.d100 !== null" class="mt-2 text-xs">
                            <template v-if="eduCheckStates[index].success">
                                <span class="font-semibold text-cthulhu-green-600">
                                    Success! Roll 1D10 and enter it:
                                </span>
                                <NumberField v-model="check.d10" :min="1" :max="10" placeholder="D10" width-class="w-16 ml-2" />
                                <span v-if="check.d10 !== null" class="ml-2 text-cthulhu-green-700">
                                    EDU {{ eduCheckStates[index].before }} → {{ eduCheckStates[index].after }} (cap 99)
                                </span>
                            </template>
                            <span v-else class="text-cthulhu-green-700">
                                {{ check.d100 }} ≤ {{ eduCheckStates[index].before }} — no improvement.
                            </span>
                        </div>
                    </li>
                </ol>
            </div>
        </div>

        <!-- Derived attributes -->
        <div class="panel p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-cthulhu-green-900">Derived attributes</h3>

            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-cthulhu-green-700">
                            <th class="py-1 pr-2 font-medium">Characteristic</th>
                            <th class="py-1 pr-2 font-medium text-center">Full</th>
                            <th class="py-1 pr-2 font-medium text-center">Half</th>
                            <th class="py-1 font-medium text-center">Fifth</th>
                        </tr>
                    </thead>
                    <tbody class="text-cthulhu-green-900">
                        <tr
                            v-for="c in CHARACTERISTICS"
                            :key="c.key"
                            class="border-t border-cthulhu-green-300/60"
                        >
                            <td class="py-1 pr-2 font-semibold">{{ c.abbr }}</td>
                            <td class="py-1 pr-2 text-center">{{ finals[c.key] ?? '—' }}</td>
                            <td class="py-1 pr-2 text-center">{{ finals[c.key] !== null ? half(finals[c.key]) : '—' }}</td>
                            <td class="py-1 text-center">{{ finals[c.key] !== null ? fifth(finals[c.key]) : '—' }}</td>
                        </tr>
                        <tr class="border-t border-cthulhu-green-300/60">
                            <td class="py-1 pr-2 font-semibold">LUCK</td>
                            <td class="py-1 pr-2 text-center">{{ finals.luck ?? '—' }}</td>
                            <td class="py-1 pr-2 text-center">{{ finals.luck !== null ? half(finals.luck) : '—' }}</td>
                            <td class="py-1 text-center">{{ finals.luck !== null ? fifth(finals.luck) : '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <dl class="mt-4 grid grid-cols-2 gap-2 text-xs sm:grid-cols-3">
                <div class="rounded-lg bg-cthulhu-green-100/60 p-2 ring-1 ring-cthulhu-green-300/60">
                    <dt class="text-cthulhu-green-700">Hit Points</dt>
                    <dd class="text-base font-bold text-cthulhu-green-900">{{ derived?.hitPoints ?? '—' }}</dd>
                    <dd class="text-[10px] text-cthulhu-green-500">(CON + SIZ) ÷ 10</dd>
                </div>
                <div class="rounded-lg bg-cthulhu-green-100/60 p-2 ring-1 ring-cthulhu-green-300/60">
                    <dt class="text-cthulhu-green-700">Sanity</dt>
                    <dd class="text-base font-bold text-cthulhu-green-900">{{ derived?.sanity ?? '—' }}</dd>
                    <dd class="text-[10px] text-cthulhu-green-500">equals POW</dd>
                </div>
                <div class="rounded-lg bg-cthulhu-green-100/60 p-2 ring-1 ring-cthulhu-green-300/60">
                    <dt class="text-cthulhu-green-700">Magic Points</dt>
                    <dd class="text-base font-bold text-cthulhu-green-900">{{ derived?.magicPoints ?? '—' }}</dd>
                    <dd class="text-[10px] text-cthulhu-green-500">POW ÷ 5</dd>
                </div>
                <div class="rounded-lg bg-cthulhu-green-100/60 p-2 ring-1 ring-cthulhu-green-300/60">
                    <dt class="text-cthulhu-green-700">Dodge</dt>
                    <dd class="text-base font-bold text-cthulhu-green-900">{{ derived?.dodge ?? '—' }}</dd>
                    <dd class="text-[10px] text-cthulhu-green-500">DEX ÷ 2</dd>
                </div>
                <div class="rounded-lg bg-cthulhu-green-100/60 p-2 ring-1 ring-cthulhu-green-300/60">
                    <dt class="text-cthulhu-green-700">Move Rate</dt>
                    <dd class="text-base font-bold text-cthulhu-green-900">
                        <template v-if="derived">
                            {{ derived.moveBase - derived.moveDeduction }}
                            <span v-if="derived.moveDeduction" class="text-[10px] font-normal text-cthulhu-green-500">
                                ({{ derived.moveBase }} − {{ derived.moveDeduction }} age)
                            </span>
                        </template>
                        <template v-else>—</template>
                    </dd>
                    <dd class="text-[10px] text-cthulhu-green-500">
                        7 if STR &amp; DEX &lt; SIZ · 9 if both &gt; SIZ · else 8
                    </dd>
                </div>
                <div class="rounded-lg bg-cthulhu-green-100/60 p-2 ring-1 ring-cthulhu-green-300/60">
                    <dt class="text-cthulhu-green-700">Damage Bonus / Build</dt>
                    <dd class="text-base font-bold text-cthulhu-green-900">
                        {{ derived ? `${derived.damageBonus} / ${derived.build}` : '—' }}
                    </dd>
                    <dd class="text-[10px] text-cthulhu-green-500">Table I: STR + SIZ</dd>
                </div>
            </dl>

            <div v-if="page.props.errors && Object.keys(page.props.errors).length" class="mt-3">
                <p v-for="(message, key) in page.props.errors" :key="key" class="text-xs text-cthulhu-blood-400">
                    {{ message }}
                </p>
            </div>

            <div class="mt-4 flex items-center justify-end gap-3">
                <p v-if="!ready" class="text-xs text-cthulhu-green-700">
                    Enter all rolls (and settle the age modifiers) to continue.
                </p>
                <button
                    type="button"
                    @click="submit"
                    :disabled="!ready || processing"
                    class="rounded-md bg-cthulhu-green-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-600 disabled:opacity-40"
                >
                    {{ processing ? 'Saving…' : 'Save characteristics' }}
                </button>
            </div>
        </div>
    </div>
</template>
