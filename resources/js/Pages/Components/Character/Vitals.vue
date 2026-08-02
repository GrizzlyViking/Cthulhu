<script setup>
import Modal from '@/Components/Modal.vue';
import { computed, ref } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { BoltIcon, HeartIcon, SparklesIcon, EyeIcon } from '@heroicons/vue/24/solid';

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
});

const modalShow = ref(false);

let modalAttributes = useForm({
    title: '',
    attribute: '',
    value: 0,
});

const maxHitPoints = computed(() =>
    Math.floor((Number(prop.character.constitution) + Number(prop.character.size)) / 5)
);

/** Ratio of current to maximum, clamped to 0–1 for the meter width. */
const ratio = (current, max) => {
    if (!max) return 0;
    return Math.min(1, Math.max(0, Number(current) / Number(max)));
};

const hitPointRatio = computed(() => ratio(prop.character.hit_points, maxHitPoints.value));
const sanityRatio = computed(() => ratio(prop.character.sanity, prop.character.power));

/** Below a third remaining the meter turns to blood. */
const meterTone = (value) => (value <= 1 / 3 ? 'bg-cthulhu-blood-400' : 'bg-cthulhu-green-600');

const saveVitals = () => {
    modalAttributes.put(route('attribute.update', { character: prop.character.slug }), {
        preserveScroll: true,
        onSuccess: () => { modalShow.value = false; },
    });
};

const toggleStatus = (attribute, currentValue) => {
    if (!prop.canEdit) {
        return;
    }
    const newValue = !currentValue;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: attribute,
        value: newValue,
    }).then(() => {
        prop.character[attribute] = newValue;
    });
};

const openAttributeModal = (title, attribute, value) => {
    if (!prop.canEdit) {
        return;
    }
    modalAttributes.title = title;
    modalAttributes.attribute = attribute;
    modalAttributes.value = value;
    modalShow.value = true;
};

const closeModal = () => {
    modalAttributes.reset();
    modalShow.value = false;
};
</script>

<template>
    <section class="panel p-4 sm:p-5">
        <dl class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <!-- Hit points -->
            <div class="card flex flex-col gap-2">
                <dt class="flex items-center gap-1.5 eyebrow">
                    <HeartIcon class="size-3.5 text-cthulhu-blood-400" aria-hidden="true" />
                    Hit points
                </dt>
                <dd>
                    <button
                        type="button"
                        :disabled="!canEdit"
                        class="tabular w-full rounded-lg text-left text-4xl font-semibold tracking-tight text-cthulhu-green-800 transition enabled:hover:text-cthulhu-green-600 disabled:cursor-default sm:text-5xl"
                        :title="canEdit ? 'Adjust hit points' : null"
                        @click="openAttributeModal('Hit Points', 'hit_points', prop.character.hit_points)"
                    >
                        {{ prop.character.hit_points }}<span class="text-xl text-cthulhu-green-500"> / {{ maxHitPoints }}</span>
                    </button>
                </dd>
                <div class="h-1.5 overflow-hidden rounded-full bg-parchment-300">
                    <div
                        class="h-full rounded-full transition-all"
                        :class="meterTone(hitPointRatio)"
                        :style="{ width: `${hitPointRatio * 100}%` }"
                    ></div>
                </div>
                <button
                    v-if="prop.character.unconscious"
                    type="button"
                    class="chip-blood self-start"
                    :disabled="!canEdit"
                    :title="canEdit ? 'Clear this condition' : null"
                    @click="toggleStatus('unconscious', prop.character.unconscious)"
                >
                    Unconscious
                </button>
            </div>

            <!-- Sanity -->
            <div class="card flex flex-col gap-2">
                <dt class="flex items-center gap-1.5 eyebrow">
                    <EyeIcon class="size-3.5 text-cthulhu-green-600" aria-hidden="true" />
                    Sanity
                </dt>
                <dd>
                    <button
                        type="button"
                        :disabled="!canEdit"
                        class="tabular w-full rounded-lg text-left text-4xl font-semibold tracking-tight text-cthulhu-green-800 transition enabled:hover:text-cthulhu-green-600 disabled:cursor-default sm:text-5xl"
                        :title="canEdit ? 'Adjust sanity' : null"
                        @click="openAttributeModal('Sanity', 'sanity', prop.character.sanity)"
                    >
                        {{ prop.character.sanity }}<span class="text-xl text-cthulhu-green-500"> / {{ prop.character.power }}</span>
                    </button>
                </dd>
                <div class="h-1.5 overflow-hidden rounded-full bg-parchment-300">
                    <div
                        class="h-full rounded-full transition-all"
                        :class="meterTone(sanityRatio)"
                        :style="{ width: `${sanityRatio * 100}%` }"
                    ></div>
                </div>
                <button
                    v-if="prop.character.temporary_insanity"
                    type="button"
                    class="chip-blood self-start"
                    :disabled="!canEdit"
                    :title="canEdit ? 'Clear this condition' : null"
                    @click="toggleStatus('temporary_insanity', prop.character.temporary_insanity)"
                >
                    Temporary insanity
                </button>
            </div>

            <!-- Luck -->
            <div class="card flex flex-col gap-2">
                <dt class="flex items-center gap-1.5 eyebrow">
                    <SparklesIcon class="size-3.5 text-cthulhu-yellow-600" aria-hidden="true" />
                    Luck
                </dt>
                <dd>
                    <button
                        type="button"
                        :disabled="!canEdit"
                        class="tabular w-full rounded-lg text-left text-4xl font-semibold tracking-tight text-cthulhu-green-800 transition enabled:hover:text-cthulhu-green-600 disabled:cursor-default sm:text-5xl"
                        :title="canEdit ? 'Adjust luck' : null"
                        @click="openAttributeModal('Luck', 'luck', prop.character.luck)"
                    >
                        {{ prop.character.luck }}
                    </button>
                </dd>
            </div>

            <!-- Magic points -->
            <div class="card flex flex-col gap-2">
                <dt class="flex items-center gap-1.5 eyebrow">
                    <BoltIcon class="size-3.5 text-cthulhu-yellow-600" aria-hidden="true" />
                    Magic points
                </dt>
                <dd>
                    <button
                        type="button"
                        :disabled="!canEdit"
                        class="tabular w-full rounded-lg text-left text-4xl font-semibold tracking-tight text-cthulhu-green-800 transition enabled:hover:text-cthulhu-green-600 disabled:cursor-default sm:text-5xl"
                        :title="canEdit ? 'Adjust magic points' : null"
                        @click="openAttributeModal('Magic Points', 'magic_points', prop.character.magic_points)"
                    >
                        {{ prop.character.magic_points }}
                    </button>
                </dd>
            </div>
        </dl>

        <Modal :show="modalShow" max-width="sm" @close="closeModal">
            <div class="bg-parchment-100 p-6">
                <label for="vital_value" class="field-label">{{ modalAttributes.title }}</label>

                <div class="mt-3 flex items-end gap-2">
                    <input
                        id="vital_value"
                        v-model="modalAttributes.value"
                        type="number"
                        inputmode="numeric"
                        autocomplete="off"
                        data-1p-ignore
                        data-lpignore="true"
                        data-bwignore
                        class="field tabular"
                    />

                    <button
                        type="button"
                        class="btn-primary shrink-0"
                        :disabled="modalAttributes.processing"
                        @click="saveVitals"
                    >
                        {{ modalAttributes.processing ? 'Saving…' : 'Save' }}
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
