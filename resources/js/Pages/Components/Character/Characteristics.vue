<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import { computed, ref, capitalize } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";

const prop = defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
});

const modalOpen = ref(false);

const closeEditModal = () => {
    modalOpen.value = false;
}

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
}

const saveCharacteristic = () => {
    modalAttributes.put(
        route('attribute.update', { character: prop.character.slug }),
        {
            preserveScroll: true,
            onSuccess: () => { modalOpen.value = false; }
        })
}

const moveRate = computed(() => prop.character.move_rate)

const attributes = ['strength', 'dexterity', 'intelligence', 'constitution', 'appearance', 'power', 'size', 'education'];
</script>

<template>
    <div class="p-3 m-3 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-3">
        <div v-for="attribute in attributes" :key="attribute">
            <div class="grid grid-cols-2 justify-between" @click="openEditModal(attribute, prop.character[attribute])">
                <div class="font-bold p-2 align-middle text-right">{{ capitalize(attribute) }}</div>
                <regular-half-fifth :skill-value="prop.character[attribute]"></regular-half-fifth>
            </div>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Move Rate</div>
            <div
                class="text-center py-3 flex-initial w-12 text-gray-900 border border-cthulhu-green-400 rounded-lg bg-gray-50 text-xs read-only:bg-gray-100">
                {{ moveRate }}
            </div>
        </div>

        <Modal max-width="sm" :show="modalOpen" @close="closeEditModal">
            <div class="bg-cthulhu-green-200 p-6 border-cthulhu-green-100 align-bottom">
                <div class="flex flex-col h-full gap-1">
                    <label
                        for="starting_value"
                        class="block text-md font-medium leading-6 text-gray-900"
                    >
                        {{ capitalize(modalAttributes.attribute) }}
                    </label>

                    <div class="flex-grow"></div>

                    <div class="flex items-end gap-2">
                        <input
                            type="number"
                            v-model="modalAttributes.value"
                            inputmode="numeric"
                            autocomplete="off"
                            data-1p-ignore
                            data-lpignore="true"
                            data-bwignore
                            class="block w-full rounded-md border-0 py-1.5
                       text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
                       placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                       focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />

                        <button
                            type="button"
                            @click="saveCharacteristic"
                            :disabled="modalAttributes.processing"
                            class="rounded-md bg-cthulhu-green-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:opacity-50"
                        >
                            {{ modalAttributes.processing ? 'Saving...' : 'Save' }}
                        </button>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>

</style>
