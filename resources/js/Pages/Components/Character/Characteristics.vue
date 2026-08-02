<script setup>
import RegularHalfFifth from '@/Pages/Components/RegularHalfFifth.vue';
import SuccessLegend from '@/Pages/Components/SuccessLegend.vue';
import { capitalize, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
});

const modalOpen = ref(false);

const closeEditModal = () => {
    modalOpen.value = false;
};

const modalAttributes = useForm({
    attribute: '',
    value: 0,
});

const openEditModal = (attribute, attribute_value) => {
    if (!prop.canEdit) {
        return;
    }
    modalAttributes.attribute = attribute;
    modalAttributes.value = attribute_value;
    modalOpen.value = true;
};

const saveCharacteristic = () => {
    modalAttributes.put(route('attribute.update', { character: prop.character.slug }), {
        preserveScroll: true,
        onSuccess: () => { modalOpen.value = false; },
    });
};

const attributes = ['strength', 'dexterity', 'intelligence', 'constitution', 'appearance', 'power', 'size', 'education'];
</script>

<template>
    <section class="panel p-4 sm:p-5">
        <header class="mb-4 flex flex-wrap items-baseline justify-between gap-2">
            <h2 class="text-base font-semibold text-cthulhu-green-900">Characteristics</h2>
            <SuccessLegend />
        </header>

        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="attribute in attributes"
                :key="attribute"
                class="flex items-center justify-between gap-3 rounded-lg bg-parchment-50 px-3 py-2 ring-1 ring-parchment-300"
            >
                <span class="text-sm font-semibold text-cthulhu-green-900">{{ capitalize(attribute) }}</span>
                <RegularHalfFifth
                    class="!w-[9rem] shrink-0"
                    :skill-value="prop.character[attribute]"
                    :interactive="canEdit"
                    :title="canEdit ? `Adjust ${attribute}` : null"
                    @click="openEditModal(attribute, prop.character[attribute])"
                />
            </div>

            <div class="flex items-center justify-between gap-3 rounded-lg bg-parchment-50 px-3 py-2 ring-1 ring-parchment-300">
                <span class="text-sm font-semibold text-cthulhu-green-900">Move rate</span>
                <div class="tabular w-[9rem] shrink-0 rounded-lg bg-parchment-200/70 py-2 text-center text-sm text-cthulhu-green-700 ring-1 ring-inset ring-cthulhu-green-800/25">
                    {{ prop.character.move_rate }}
                </div>
            </div>
        </div>

        <Modal max-width="sm" :show="modalOpen" @close="closeEditModal">
            <div class="bg-parchment-100 p-6">
                <label for="characteristic_value" class="field-label">
                    {{ capitalize(modalAttributes.attribute) }}
                </label>

                <div class="mt-3 flex items-end gap-2">
                    <input
                        id="characteristic_value"
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
                        @click="saveCharacteristic"
                    >
                        {{ modalAttributes.processing ? 'Saving…' : 'Save' }}
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
