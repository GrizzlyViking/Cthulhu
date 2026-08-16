<script setup>
import { computed, ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StepperHeader from '@/Pages/Components/Wizard/StepperHeader.vue';
import StepProfile from '@/Pages/Components/Wizard/StepProfile.vue';
import StepCharacteristics from '@/Pages/Components/Wizard/StepCharacteristics.vue';
import StepOccupation from '@/Pages/Components/Wizard/StepOccupation.vue';
import StepSkills from '@/Pages/Components/Wizard/StepSkills.vue';
import StepBackstory from '@/Pages/Components/Wizard/StepBackstory.vue';
import StepEquipment from '@/Pages/Components/Wizard/StepEquipment.vue';
import { eraLabel } from '@/Pages/Components/Wizard/wizardData.js';

const props = defineProps({
    draft: { type: Object, default: null },
    occupations: { type: Array, default: () => [] },
    era: { type: String, default: '1920s' },
    eras: { type: Array, default: () => [] },
    skillOptions: { type: Array, default: () => [] },
    characteristics: { type: Object, default: () => ({}) },
    occupationPoints: { type: Number, default: null },
    personalPoints: { type: Number, default: null },
    wealth: { type: Object, default: null },
});

const steps = [
    'Profile',
    'Characteristics',
    'Occupation',
    'Skills',
    'Backstory',
    'Equipment',
];

const furthest = computed(() => Math.min(props.draft?.wizard_step ?? 0, 5));

const currentStep = ref(furthest.value);

// A fresh page load (or draft deletion elsewhere) resets to the profile step.
watch(
    () => props.draft?.id ?? null,
    (id, previous) => {
        if (id === null) currentStep.value = 0;
        else if (previous === null) currentStep.value = Math.min(1, furthest.value);
    }
);

const goTo = (index) => {
    if (index <= furthest.value) currentStep.value = index;
};

const advance = () => {
    currentStep.value = Math.min(currentStep.value + 1, 5);
};
</script>

<template>
    <Head title="Create investigator" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">
                Create investigator
                <span class="ms-2 font-sans text-sm font-normal tracking-normal text-cthulhu-green-200">
                    ({{ eraLabel(era) }})
                </span>
            </h1>
        </template>

        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4">
                <StepperHeader
                    :steps="steps"
                    :current="currentStep"
                    :furthest="furthest"
                    @select="goTo"
                />

                <StepProfile v-if="currentStep === 0" :draft="draft" @advance="advance" />

                <StepCharacteristics
                    v-else-if="currentStep === 1 && draft"
                    :draft="draft"
                    @advance="advance"
                />

                <StepOccupation
                    v-else-if="currentStep === 2 && draft"
                    :draft="draft"
                    :occupations="occupations"
                    :eras="eras"
                    :skill-options="skillOptions"
                    :characteristics="characteristics"
                    @advance="advance"
                />

                <StepSkills
                    v-else-if="currentStep === 3 && draft"
                    :draft="draft"
                    :occupations="occupations"
                    :occupation-points="occupationPoints"
                    :personal-points="personalPoints"
                    @advance="advance"
                />

                <StepBackstory
                    v-else-if="currentStep === 4 && draft"
                    :draft="draft"
                    @advance="advance"
                />

                <StepEquipment
                    v-else-if="currentStep === 5 && draft"
                    :draft="draft"
                    :era="era"
                    :wealth="wealth"
                    @advance="advance"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
