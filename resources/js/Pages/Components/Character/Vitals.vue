<script setup>
import {debounce} from "lodash";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {ref} from "vue";
import axios from "axios";
import {router, useForm} from "@inertiajs/vue3";

const prop = defineProps({
    character: Object,
    editable: Boolean
});

const isHitPointsModalOpen = ref(false);
const isSanityModalOpen = ref(false);

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

const updateAttribute = debounce((skill, value) => {
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


let adjustHitPoints = debounce((event) => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: 'hit_points',
        value: event.target.value
    }).then(
        () => {
            if (Math.floor(prop.character.hit_points / 2) > Number(event.target.value)) {
                openHitPointsModal();
            }
            prop.character.hit_points = event.target.value;
        }
    );
}, 600)

let adjustLuck = debounce(() => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: 'luck',
        value: prop.character.luck
    });
}, 600);

let adjustMagicPoints = debounce(() => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: 'magic_points',
        value: prop.character.magic_points
    });
}, 600);

let adjustSanity = debounce((event) => {
    if ((Number(prop.character.sanity) - 5) > Number(event.target.value)) {
        openSanityModal();
    }
    prop.character.sanity = event.target.value;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: 'sanity',
        value: event.target.value
    });
}, 600)

let unconscious = (setValue) => {
    prop.character.unconscious = setValue;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: 'unconscious',
        value: prop.character.unconscious
    }).then(() => {
        closeModal()
    });

}

let temporaryInsanity = (setValue) => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
    }), {
        attribute: 'temporary_insanity',
        value: setValue
    }).then(() => {
        prop.character.temporary_insanity = setValue;
        closeModal();
    });

}

const openHitPointsModal = () => {
    isHitPointsModalOpen.value = true;
};
const openSanityModal = () => {
    isSanityModalOpen.value = true;
};

const closeModal = () => {
    isHitPointsModalOpen.value = false;
    isSanityModalOpen.value = false;
};
</script>

<template>
    <div>
        <dl class="grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative flex flex-col bg-gray-400/5 p-8">

                <dd class="text-3xl font-semibold tracking-tight text-gray-900">
                    <input :value="prop.character.hit_points"
                           @input="adjustHitPoints($event)"
                           type="text"
                           class="text-cthulhu-green-800 transition border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight bg-transparent"
                    >
                </dd>
                <dt class="text-sm font-semibold leading-6 text-gray-600">Hit points</dt>
                <dd class="text-cthulhu-green-800 text-xs">Max hit points:
                    {{ Math.floor((Number(character.constitution) + Number(character.size)) / 5) }}
                </dd>
                <div @click="unconscious(!prop.character.unconscious)" v-if="prop.character.unconscious"
                     class="absolute inset-x-0 top-20 flex justify-center items-center">
                    <h2 class="border w-36 rounded-md border-red-600 text-2xl text-red-400 rotate-45 font-semibold tracking-tight">
                        Uncuncious</h2>
                </div>
            </div>
            <div class="relative flex flex-col bg-gray-400/5 p-8">
                <dd class="text-3xl font-semibold tracking-tight text-gray-900">
                    <input :value="prop.character.sanity"
                           @input="adjustSanity"
                           class="text-cthulhu-green-800 border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight bg-transparent"
                           type="text">
                </dd>
                <dt class="text-sm font-semibold leading-6 text-gray-600">Sanity</dt>
                <dd class="text-cthulhu-green-800 text-xs">Starting sanity: {{ prop.character.power }}</dd>
                <div @click="temporaryInsanity(!prop.character.temporary_insanity)"
                     v-if="prop.character.temporary_insanity"
                     class="absolute inset-x-0 top-10 flex justify-center items-center">
                    <h2 class="border w-36 rounded-md border-red-600 text-2xl text-red-400 rotate-45 font-semibold tracking-tight">
                        Temporary Insanity</h2>
                </div>
            </div>
            <div class="flex flex-col bg-gray-400/5 p-8">
                <dt class="text-sm font-semibold leading-6 text-gray-600">Luck</dt>
                <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900">
                    <input v-model="prop.character.luck" type="text" @input="adjustLuck"
                           class="text-cthulhu-green-800 bg-cthulhu-green-200 border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
                </dd>
            </div>
            <div class="flex flex-col bg-gray-400/5 p-8">
                <dd class="text-3xl font-semibold tracking-tight text-gray-900">
                    <input v-model="prop.character.magic_points" type="text" @input="adjustMagicPoints"
                           class="text-cthulhu-green-800 bg-cthulhu-green-200 border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
                </dd>
                <dt class="text-sm font-semibold leading-6 text-gray-600">Magic points</dt>
            </div>
        </dl>

        <modal-popup :is-open="modalOpen" :initial-focus="cancelBtnRef"
                     @modal-close="closeEditModal"
        >
            <template #title>Edit skill</template>
            <template #default>
                <form>
                    <div>
                        <label for="starting_value" class="block text-sm font-medium leading-6 text-gray-900">{{
                                modalAttributes.attribute_name
                            }}</label>
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
