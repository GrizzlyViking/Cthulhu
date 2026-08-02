<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { CheckCircleIcon, ChevronDownIcon } from '@heroicons/vue/20/solid';
import { occupationPool } from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    draft: { type: Object, required: true },
    occupations: { type: Array, required: true },
});

const emit = defineEmits(['advance']);

const page = usePage();
const processing = ref(false);

const selectedId = ref(props.draft.occupation_id ?? null);
const expandedId = ref(props.draft.occupation_id ?? null);

const skillNames = computed(() => {
    const map = {};
    (props.draft.skills ?? []).forEach((skill) => {
        map[skill.slug] = skill.display_name;
    });
    return map;
});

const nameFor = (slug) =>
    skillNames.value[slug] ??
    slug.replace(/[-_]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());

const skillList = (occupation) =>
    occupation.skills.map((entry) => {
        if (typeof entry === 'string') return nameFor(entry);
        return entry.label ?? nameFor(entry.type);
    });

const poolFor = (occupation) => occupationPool(occupation, props.draft);

const toggle = (occupation) => {
    expandedId.value = expandedId.value === occupation.id ? null : occupation.id;
};

const select = (occupation) => {
    selectedId.value = occupation.id;
    expandedId.value = occupation.id;
};

const submit = () => {
    if (!selectedId.value) return;
    processing.value = true;
    router.put(
        route('character.wizard.occupation', { character: props.draft.slug }),
        { occupation_id: selectedId.value },
        {
            preserveScroll: true,
            onSuccess: () => emit('advance'),
            onFinish: () => (processing.value = false),
        }
    );
};
</script>

<template>
    <div class="panel p-4 sm:p-6">
        <h2 class="text-base font-semibold leading-7 text-cthulhu-green-900">
            Determine occupation
        </h2>
        <p class="mt-1 text-sm leading-6 text-cthulhu-green-700">
            An occupation ties together a cluster of skills and sets your occupation skill
            point pool. Its actual effect in play is limited — it is the seed of your
            backstory and expertise. Skill point pools below are computed from
            <span class="font-semibold">your</span> characteristics.
        </p>

        <ul class="mt-5 flex flex-col gap-3">
            <li
                v-for="occupation in occupations"
                :key="occupation.id"
                class="rounded-lg ring-1 transition"
                :class="selectedId === occupation.id
                    ? 'ring-2 ring-cthulhu-green-600 bg-cthulhu-green-100'
                    : 'ring-cthulhu-green-300/60 bg-cthulhu-green-100/60'"
            >
                <button type="button" class="w-full p-3 text-left" @click="toggle(occupation)">
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex items-center gap-1.5 text-sm font-bold text-cthulhu-green-900">
                            <CheckCircleIcon
                                v-if="selectedId === occupation.id"
                                class="size-5 text-cthulhu-green-600"
                                aria-hidden="true"
                            />
                            {{ occupation.name }}
                        </span>
                        <span class="flex items-center gap-2 shrink-0">
                            <span class="rounded-full bg-cthulhu-green-300/70 px-2 py-0.5 text-xs font-semibold text-cthulhu-green-900">
                                {{ poolFor(occupation) }} pts
                            </span>
                            <ChevronDownIcon
                                class="size-4 text-cthulhu-green-700 transition-transform"
                                :class="{ 'rotate-180': expandedId === occupation.id }"
                                aria-hidden="true"
                            />
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-cthulhu-green-700">
                        {{ occupation.formula_label }} · Credit Rating {{ occupation.credit_rating_min }}–{{ occupation.credit_rating_max }}
                    </p>
                </button>

                <div v-if="expandedId === occupation.id" class="px-3 pb-3">
                    <p class="text-xs leading-5 text-cthulhu-green-700">
                        {{ occupation.description }}
                    </p>
                    <p class="mt-2 text-[10px] font-semibold uppercase tracking-wide text-cthulhu-green-500">
                        Occupation skills
                    </p>
                    <ul class="mt-1 flex flex-wrap gap-1">
                        <li
                            v-for="(skill, index) in skillList(occupation)"
                            :key="index"
                            class="rounded-full bg-cthulhu-green-200 px-2 py-0.5 text-[11px] text-cthulhu-green-800 ring-1 ring-cthulhu-green-300"
                        >
                            {{ skill }}
                        </li>
                    </ul>
                    <button
                        v-if="selectedId !== occupation.id"
                        type="button"
                        @click="select(occupation)"
                        class="mt-3 rounded-md bg-cthulhu-green-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-cthulhu-green-600"
                    >
                        Choose {{ occupation.name }}
                    </button>
                </div>
            </li>
        </ul>

        <p v-if="page.props.errors.occupation_id" class="mt-2 text-xs text-cthulhu-blood-400">
            {{ page.props.errors.occupation_id }}
        </p>

        <div class="mt-5 flex items-center justify-end gap-3">
            <p v-if="!selectedId" class="text-xs text-cthulhu-green-700">
                Select an occupation to continue.
            </p>
            <button
                type="button"
                @click="submit"
                :disabled="!selectedId || processing"
                class="rounded-md bg-cthulhu-green-800 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-600 disabled:opacity-40"
            >
                {{ processing ? 'Saving…' : 'Save occupation' }}
            </button>
        </div>
    </div>
</template>
