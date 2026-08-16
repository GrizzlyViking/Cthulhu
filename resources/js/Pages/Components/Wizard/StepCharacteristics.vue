<script setup>
import { computed, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import CharacteristicsBasic from '@/Pages/Components/Wizard/CharacteristicsBasic.vue';
import CharacteristicsPointBuy from '@/Pages/Components/Wizard/CharacteristicsPointBuy.vue';
import CharacteristicsRoll from '@/Pages/Components/Wizard/CharacteristicsRoll.vue';
import {
    CHARACTERISTICS,
    POINT_BUY,
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

/* ------------------------------------------------------------ methods -- */

/**
 * How the player arrives at their eight numbers. Each method owns its own
 * inputs and hands back the finished percentages; everything below — the
 * derived attributes and the save — is the same whichever is chosen.
 */
const METHODS = [
    {
        key: 'basic',
        label: 'Enter values',
        blurb: 'Type the finished numbers. No dice help, no arithmetic done for you.',
    },
    {
        key: 'roll',
        label: 'Roll the dice',
        blurb: 'Type your dice totals; we multiply by five and settle the age modifiers.',
    },
    {
        key: 'points',
        label: 'Point Buy',
        blurb: `Share ${POINT_BUY.pool} points among the eight characteristics instead of rolling.`,
    },
];

const method = ref('basic');

const EMPTY_FINALS = Object.fromEntries(
    [...CHARACTERISTICS.map((c) => c.key), 'luck'].map((key) => [key, null])
);

const finals = ref({ ...EMPTY_FINALS });
const ready = ref(false);

// Switching method starts that method's sheet blank; the incoming component
// fills these in again as it is set up.
watch(method, () => {
    finals.value = { ...EMPTY_FINALS };
    ready.value = false;
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
                How do you want to set your characteristics?
            </h2>

            <div class="mt-3 grid gap-2 sm:grid-cols-3">
                <button
                    v-for="m in METHODS"
                    :key="m.key"
                    type="button"
                    @click="method = m.key"
                    :aria-pressed="method === m.key"
                    class="rounded-lg p-3 text-left ring-1"
                    :class="method === m.key
                        ? 'bg-cthulhu-green-800 text-white ring-cthulhu-green-800'
                        : 'bg-cthulhu-green-100/60 text-cthulhu-green-900 ring-cthulhu-green-300/60 hover:bg-cthulhu-green-100'"
                >
                    <p class="text-sm font-semibold">{{ m.label }}</p>
                    <p
                        class="mt-0.5 text-xs"
                        :class="method === m.key ? 'text-cthulhu-green-100' : 'text-cthulhu-green-700'"
                    >
                        {{ m.blurb }}
                    </p>
                </button>
            </div>

            <p class="mt-3 text-xs text-cthulhu-green-700">
                Changing method clears what you have typed.
            </p>

            <div v-if="alreadySaved" class="mt-3 rounded-lg bg-cthulhu-green-100 p-3 text-xs text-cthulhu-green-700 ring-1 ring-cthulhu-green-300">
                <p class="font-semibold">Saved characteristics</p>
                <p class="mt-1">{{ savedSummary }}</p>
                <p class="mt-1">Enter values below only if you want to replace them.</p>
                <button
                    type="button"
                    @click="emit('advance')"
                    class="mt-2 rounded-md bg-cthulhu-green-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-cthulhu-green-600"
                >
                    Keep saved values →
                </button>
            </div>
        </div>

        <!-- Age modifiers are the player's own business unless the app is
             doing the arithmetic for them. -->
        <div v-if="mods && method !== 'roll'" class="panel p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-cthulhu-green-900">
                Age modifiers — {{ mods.bracket }} (age {{ draft.age }})
            </h3>
            <p class="mt-1 text-xs text-cthulhu-green-700">{{ mods.summary }}</p>
            <p class="mt-2 text-xs text-cthulhu-green-700">
                Apply these yourself to the values you enter — nothing here is adjusted for you.
            </p>
        </div>

        <CharacteristicsBasic
            v-if="method === 'basic'"
            v-model:finals="finals"
            v-model:ready="ready"
        />
        <CharacteristicsRoll
            v-else-if="method === 'roll'"
            :draft="draft"
            v-model:finals="finals"
            v-model:ready="ready"
        />
        <CharacteristicsPointBuy
            v-else
            v-model:finals="finals"
            v-model:ready="ready"
        />

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
                    Fill in every characteristic
                    <template v-if="method === 'roll'">(and settle the age modifiers)</template>
                    <template v-else-if="method === 'points'">(and spend the whole pool)</template>
                    to continue.
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
