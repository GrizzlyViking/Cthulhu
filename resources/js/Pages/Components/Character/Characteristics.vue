<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {computed, ref, capitalize} from "vue";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {router, useForm} from "@inertiajs/vue3";
import {debounce} from "lodash";
const prop = defineProps({character: Object, editable: Boolean});
let modalOpen = ref(false);

const closeEditModal = () => {
    modalOpen.value = false;
}

let modalAttributes = useForm({
    character_id: prop.character.id,
    attribute_name: '',
    attribute_value: 0,
});

const openEditModal = (attribute, attribute_value) => {
    modalAttributes.character_id = prop.character.id;
    modalAttributes.attribute_name = attribute;
    modalAttributes.attribute_value = attribute_value;
    modalOpen.value = true;
}

const updateAttribute =  debounce((skill, value) => {
    router.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: skill,
        value: Number(value)
    }, {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal();
        }
    });
}, 600);

const moveRate = computed(() => prop.character.move_rate)
const cancelBtnRef = ref(null);

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
        <modal-popup :is-open="modalOpen" :initial-focus="cancelBtnRef" @modal-close="closeEditModal">
            <template #title>Edit skill</template>
            <template #default>
                <form>
                    <div>
                        <label for="starting_value" class="block text-sm font-medium leading-6 text-gray-900">{{ modalAttributes.attribute_name }}</label>
                        <div class="mt-2">
                            <input type="number"
                                   v-model="modalAttributes.attribute_value"
                                   inputmode="numeric"
                                   autocomplete="off"
                                   data-1p-ignore
                                   data-lpignore="true"
                                   data-bwignore
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>
                </form>
            </template>
            <template #buttons>
                <div class="mt-4 flex justify-between gap-2">
                    <button
                        type="button"
                        ref="cancelBtnRef"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="closeEditModal"
                    >Cancel
                    </button>
                    <button
                        type="button"
                        ref="cancelBtnRef"
                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="() => updateAttribute(modalAttributes.attribute_name, modalAttributes.attribute_value)"
                    >
                        Update
                    </button>
                </div>
            </template>
        </modal-popup>
    </div>
</template>

<style scoped>

</style>
