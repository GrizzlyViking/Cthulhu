<script setup>
import { computed, reactive, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import NumberField from '@/Pages/Components/Wizard/NumberField.vue';
import SkillAllocationRow from '@/Pages/Components/Wizard/SkillAllocationRow.vue';
import HelpDisclosure from '@/Pages/Components/Wizard/HelpDisclosure.vue';
import { half, occupationPool } from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    draft: { type: Object, required: true },
    occupations: { type: Array, required: true },
    occupationPoints: { type: Number, default: null },
    personalPoints: { type: Number, default: null },
});

const emit = defineEmits(['advance']);

const page = usePage();
const processing = ref(false);

const occupation = computed(() =>
    props.occupations.find((o) => o.id === props.draft.occupation_id) ?? null
);

const occPool = computed(
    () => props.occupationPoints ?? occupationPool(occupation.value, props.draft)
);
const persPool = computed(
    () => props.personalPoints ?? (props.draft.intelligence ?? 0) * 2
);

/* ------------------------------------------------------------- lookups -- */

const skillsBySlug = computed(() => {
    const map = {};
    (props.draft.skills ?? []).forEach((skill) => {
        map[skill.slug] = skill;
    });
    return map;
});

const nameFor = (slug) =>
    skillsBySlug.value[slug]?.display_name ??
    slug.replace(/[-_]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());

const baseFor = (slug) => {
    if (slug === 'language_own') return props.draft.education ?? 0;
    if (slug === 'dodge') return half(props.draft.dexterity ?? 0);
    return skillsBySlug.value[slug]?.starting_value ?? 0;
};

const noteFor = (slug) => {
    if (slug === 'language_own') return 'base = EDU';
    if (slug === 'dodge') return 'base = DEX ÷ 2';
    return '';
};

/* --------------------------------------------------- occupation layout -- */

const fixedSlugs = computed(() =>
    (occupation.value?.skills ?? []).filter(
        (entry) => typeof entry === 'string' && entry !== 'credit_rating'
    )
);

const choiceGroups = computed(() =>
    (occupation.value?.skills ?? []).filter(
        (entry) => typeof entry === 'object' && entry?.type === 'choice'
    )
);

const anySlots = computed(() =>
    (occupation.value?.skills ?? [])
        .filter((entry) => typeof entry === 'object' && entry?.type === 'any')
        .flatMap((entry) => Array.from({ length: entry.count ?? 1 }, () => entry.label ?? 'any skill'))
);

/* --------------------------------------------------------- allocations -- */

const occAlloc = reactive({});
const persAlloc = reactive({});
const creditRating = ref(null);

const wildcardSlugs = ref([]);
const wildcardFilters = ref([]);

const sum = (pool) =>
    Object.values(pool).reduce((total, v) => total + (Number(v) || 0), 0);

const occSpent = computed(() => sum(occAlloc) + (creditRating.value ?? 0));
const persSpent = computed(() => sum(persAlloc));
const occRemaining = computed(() => occPool.value - occSpent.value);
const persRemaining = computed(() => persPool.value - persSpent.value);

const otherPool = (slug, pool) => Number(pool[slug]) || 0;

/* ------------------------------------------------------------ wildcards -- */

const listedSlugs = computed(() => {
    const set = new Set(fixedSlugs.value);
    choiceGroups.value.forEach((group) => (group.options ?? []).forEach((s) => set.add(s)));
    set.add('credit_rating');
    set.add('cthulhu_mythos');
    return set;
});

const wildcardOptions = (index) => {
    const filter = (wildcardFilters.value[index] ?? '').toLowerCase();
    const taken = new Set(
        wildcardSlugs.value.filter((slug, i) => slug && i !== index)
    );
    return (props.draft.skills ?? [])
        .filter(
            (skill) =>
                !listedSlugs.value.has(skill.slug) &&
                !taken.has(skill.slug) &&
                (!filter || skill.display_name.toLowerCase().includes(filter))
        )
        .map((skill) => skill.slug);
};

const setWildcard = (index, slug) => {
    const previous = wildcardSlugs.value[index];
    if (previous && previous !== slug) delete occAlloc[previous];
    wildcardSlugs.value[index] = slug;
};

/* ----------------------------------------------------- personal search -- */

const personalSearch = ref('');

const personalRows = computed(() => {
    const search = personalSearch.value.trim().toLowerCase();
    const allocated = new Set(
        Object.keys(persAlloc).filter((slug) => (Number(persAlloc[slug]) || 0) > 0)
    );
    return (props.draft.skills ?? [])
        .filter((skill) => skill.slug !== 'credit_rating')
        .filter((skill) => {
            if (allocated.has(skill.slug)) return true;
            if (!search) return false;
            return skill.display_name.toLowerCase().includes(search);
        })
        .sort((a, b) => a.display_name.localeCompare(b.display_name));
});

/* ------------------------------------------------------------ validity -- */

const crInRange = computed(() => {
    if (!occupation.value) return false;
    const cr = creditRating.value ?? 0;
    return (
        cr >= occupation.value.credit_rating_min &&
        cr <= occupation.value.credit_rating_max
    );
});

const ready = computed(
    () => occRemaining.value >= 0 && persRemaining.value >= 0 && crInRange.value
);

const unspentWarning = computed(
    () => occRemaining.value > 0 || persRemaining.value > 0
);

const alreadySaved = computed(() => props.draft.wizard_step >= 4);

/* -------------------------------------------------------------- submit -- */

const cleanPool = (pool) => {
    const result = {};
    Object.entries(pool).forEach(([slug, points]) => {
        const value = Number(points) || 0;
        if (value >= 1) result[slug] = value;
    });
    return result;
};

const submit = () => {
    const occupationPayload = cleanPool(occAlloc);
    if ((creditRating.value ?? 0) >= 1) {
        occupationPayload.credit_rating = creditRating.value;
    }

    processing.value = true;
    router.put(
        route('character.wizard.skills', { character: props.draft.slug }),
        {
            occupation: occupationPayload,
            personal: cleanPool(persAlloc),
        },
        {
            preserveScroll: true,
            onSuccess: () => emit('advance'),
            onFinish: () => (processing.value = false),
        }
    );
};

const errorMessages = computed(() => Object.values(page.props.errors ?? {}));
</script>

<template>
    <div class="flex flex-col gap-3">
        <!-- Sticky remaining-points counters -->
        <div class="sticky top-12 z-[5] flex justify-center gap-2">
            <span
                class="rounded-full px-3 py-1 text-xs font-bold shadow-sm ring-1 ring-cthulhu-green-300"
                :class="occRemaining < 0
                    ? 'bg-cthulhu-blood-200 text-white'
                    : 'bg-cthulhu-green-100 text-cthulhu-green-900'"
            >
                Occupation: {{ occRemaining }} left
            </span>
            <span
                class="rounded-full px-3 py-1 text-xs font-bold shadow-sm ring-1 ring-cthulhu-green-300"
                :class="persRemaining < 0
                    ? 'bg-cthulhu-blood-200 text-white'
                    : 'bg-cthulhu-green-100 text-cthulhu-green-900'"
            >
                Personal: {{ persRemaining }} left
            </span>
        </div>

        <div class="panel p-4 sm:p-6">
            <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
                Occupation skills — {{ occupation?.name }}
            </h2>
            <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
                Spend your <span class="font-semibold">{{ occPool }}</span> occupation points
                ({{ occupation?.formula_label }}) among the skills below. Points add to each
                skill's base chance.
            </p>

            <div v-if="alreadySaved" class="mt-3 rounded-lg bg-cthulhu-green-100 p-3 text-xs text-cthulhu-green-700 ring-1 ring-cthulhu-green-300">
                <p>
                    You have already allocated skill points. Saving again replaces the previous
                    allocation (values reset to base + new points).
                </p>
                <button
                    type="button"
                    @click="emit('advance')"
                    class="mt-2 rounded-md bg-cthulhu-green-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-cthulhu-green-600"
                >
                    Keep saved allocation →
                </button>
            </div>

            <div class="mt-4 flex flex-col gap-2">
                <SkillAllocationRow
                    v-for="slug in fixedSlugs"
                    :key="slug"
                    :name="nameFor(slug)"
                    :base="baseFor(slug)"
                    :note="noteFor(slug)"
                    :other-points="otherPool(slug, persAlloc)"
                    v-model:points="occAlloc[slug]"
                />

                <!-- Credit Rating -->
                <div class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-cthulhu-green-900">Credit Rating</p>
                            <p class="text-[11px] text-cthulhu-green-700">
                                must be {{ occupation?.credit_rating_min }}–{{ occupation?.credit_rating_max }}
                                for a {{ occupation?.name }} · determines wealth &amp; living standard
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <NumberField
                                v-model="creditRating"
                                :min="occupation?.credit_rating_min ?? 0"
                                :max="occupation?.credit_rating_max ?? 99"
                                placeholder="0"
                                width-class="w-14"
                            />
                        </div>
                    </div>
                    <p v-if="!crInRange" class="mt-1 text-xs font-medium text-cthulhu-blood-400">
                        Allocate between {{ occupation?.credit_rating_min }} and
                        {{ occupation?.credit_rating_max }} points to Credit Rating.
                    </p>
                </div>

                <!-- Choice groups -->
                <template v-for="(group, groupIndex) in choiceGroups" :key="`choice-${groupIndex}`">
                    <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-cthulhu-green-700">
                        {{ group.label ?? 'Choose' }} — allocate to any of these
                    </p>
                    <SkillAllocationRow
                        v-for="slug in group.options"
                        :key="`${groupIndex}-${slug}`"
                        :name="nameFor(slug)"
                        :base="baseFor(slug)"
                        :note="noteFor(slug)"
                        :other-points="otherPool(slug, persAlloc)"
                        v-model:points="occAlloc[slug]"
                    />
                </template>

                <!-- Any-skill wildcards -->
                <template v-for="(label, index) in anySlots" :key="`any-${index}`">
                    <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-cthulhu-green-700">
                        {{ label }}
                    </p>
                    <div class="rounded-lg bg-cthulhu-green-100/60 p-3 ring-1 ring-cthulhu-green-300/60">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <input
                                type="text"
                                :value="wildcardFilters[index] ?? ''"
                                @input="wildcardFilters[index] = $event.target.value"
                                placeholder="Search skills…"
                                class="w-full sm:w-40 rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600"
                            />
                            <select
                                :value="wildcardSlugs[index] ?? ''"
                                @change="setWildcard(index, $event.target.value)"
                                class="w-full flex-1 rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600"
                            >
                                <option value="">Select skill…</option>
                                <option v-for="slug in wildcardOptions(index)" :key="slug" :value="slug">
                                    {{ nameFor(slug) }} (base {{ baseFor(slug) }})
                                </option>
                            </select>
                        </div>
                        <div v-if="wildcardSlugs[index]" class="mt-2">
                            <SkillAllocationRow
                                :name="nameFor(wildcardSlugs[index])"
                                :base="baseFor(wildcardSlugs[index])"
                                :note="noteFor(wildcardSlugs[index])"
                                :other-points="otherPool(wildcardSlugs[index], persAlloc)"
                                v-model:points="occAlloc[wildcardSlugs[index]]"
                            />
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Personal interest pool -->
        <div class="panel p-4 sm:p-6">
            <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
                Personal interest skills
            </h2>
            <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
                Hobbies and life experience: spend
                <span class="font-semibold">{{ persPool }}</span> points (INT × 2) on any
                skills — including topping up occupation skills. Cthulhu Mythos is off limits,
                and Credit Rating only takes occupation points.
            </p>

            <input
                type="text"
                v-model="personalSearch"
                placeholder="Type to search skills…"
                class="mt-3 w-full rounded-md border-0 py-1.5 text-sm text-cthulhu-green-900 shadow-sm ring-1 ring-inset ring-parchment-400 placeholder:text-cthulhu-green-500 focus:ring-2 focus:ring-inset focus:ring-cthulhu-green-600"
            />

            <div class="mt-3 flex flex-col gap-2">
                <p v-if="personalRows.length === 0" class="text-xs text-cthulhu-green-700">
                    Search above to find skills; allocated skills stay pinned here.
                </p>
                <SkillAllocationRow
                    v-for="skill in personalRows"
                    :key="skill.slug"
                    :name="skill.display_name"
                    :base="baseFor(skill.slug)"
                    :note="noteFor(skill.slug)"
                    :other-points="otherPool(skill.slug, occAlloc)"
                    :disabled="skill.slug === 'cthulhu_mythos'"
                    disabled-reason="No skill points may be spent on Cthulhu Mythos — such knowledge is paid for in Sanity, not study."
                    v-model:points="persAlloc[skill.slug]"
                />
            </div>

            <div class="mt-3">
                <HelpDisclosure label="How skill points work">
                    <p>
                        Points are added to a skill's printed base chance. Own Language starts
                        at your EDU and Dodge at half your DEX. Not every listed skill needs
                        points — but undistributed points are lost when you finish this step.
                    </p>
                </HelpDisclosure>
            </div>
        </div>

        <!-- Save bar -->
        <div class="panel p-4 sm:p-6">
            <p v-if="unspentWarning" class="rounded-lg bg-cthulhu-yellow-200 px-3 py-2 text-xs font-medium text-cthulhu-green-900 ring-1 ring-cthulhu-yellow-600">
                Unspent points are lost! Occupation: {{ Math.max(0, occRemaining) }} ·
                Personal: {{ Math.max(0, persRemaining) }} remaining.
            </p>
            <p v-if="occRemaining < 0" class="mt-2 text-xs font-medium text-cthulhu-blood-400">
                Occupation pool exceeded by {{ -occRemaining }} points.
            </p>
            <p v-if="persRemaining < 0" class="mt-2 text-xs font-medium text-cthulhu-blood-400">
                Personal pool exceeded by {{ -persRemaining }} points.
            </p>

            <div v-if="errorMessages.length" class="mt-2 flex flex-col gap-1">
                <p v-for="(message, index) in errorMessages" :key="index" class="text-xs text-cthulhu-blood-400">
                    {{ message }}
                </p>
            </div>

            <div class="mt-4 flex justify-end">
                <button
                    type="button"
                    @click="submit"
                    :disabled="!ready || processing"
                    class="rounded-md bg-cthulhu-green-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-600 disabled:opacity-40"
                >
                    {{ processing ? 'Saving…' : 'Save skill points' }}
                </button>
            </div>
        </div>
    </div>
</template>
