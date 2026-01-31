<script setup>
import {debounce} from "lodash";
import Modal from "/resources/js/Components/Modal.vue";
import {ref} from "vue";
import axios from "axios";
import {useForm} from "@inertiajs/vue3";

const prop = defineProps({
    character: Object,
    editable: Boolean
});

const modalShow = ref(false);
const isSanityModalOpen = ref(false);

let modalAttributes = useForm({
    character_id: prop.character.id,
    title: '',
    attribute: '',
    value: 0,
});

const saveVitals = () => {
    modalAttributes.put(
        route('attribute.update', {character: prop.character.slug}),
        {
            preserveScroll: true,
            onSuccess: modalShow.value = false
        })
}

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
    modalAttributes.title = "Hit Points";
    modalAttributes.attribute = 'hit_points';
    modalAttributes.value = prop.character.hit_points;
    // isHitPointsModalOpen.value = true;
    modalShow.value = true;
};
const openSanityModal = () => {
    modalAttributes.title = "Sanity";
    modalAttributes.attribute = 'sanity';
    modalAttributes.value = prop.character.sanity;
    modalShow.value = true;
};

const openLuckModal = () => {
    modalAttributes.title = "Luck";
    modalAttributes.attribute = 'luck';
    modalAttributes.value = prop.character.luck;
    modalShow.value = true;
};

const openMagicPointsModal = () => {
    modalAttributes.title = "Magic Points";
    modalAttributes.attribute = 'magic_points';
    modalAttributes.value = prop.character.magic_points;
    modalShow.value = true;
};

const resetModal = () => {
    modalAttributes.attribute_name = '';
    modalAttributes.attribute_value = 0;
}

const closeModal = () => {
    resetModal();
    modalShow.value = false;
};
</script>

<template>
    <div>
        <dl class="grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative flex flex-col bg-gray-400/5 p-8">

                <dd class="font-semibold tracking-tight text-cthulhu-green-800 text-5xl" @click="openHitPointsModal">
                    {{ prop.character.hit_points }}
                </dd>
                <dt class="text-sm font-semibold leading-6 text-gray-600">Hit points</dt>
                <dd class="text-cthulhu-green-800 text-xs">
                    Max hit points:
                    {{ Math.floor((Number(character.constitution) + Number(character.size)) / 5) }}
                </dd>
                <div @click="unconscious(!prop.character.unconscious)" v-if="prop.character.unconscious"
                     class="absolute inset-x-0 top-20 flex justify-center items-center">
                    <h2 class="border w-36 rounded-md border-red-600 text-2xl text-red-400 rotate-45 font-semibold tracking-tight">
                        Uncuncious</h2>
                </div>
            </div>
            <div class="relative flex flex-col bg-gray-400/5 p-8">
                <dd class="font-semibold tracking-tight text-cthulhu-green-800 text-5xl" @click="openSanityModal">
                    {{ prop.character.sanity }}
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
                <dd class="font-semibold tracking-tight text-cthulhu-green-800 text-5xl" @click="openLuckModal">
                    {{ prop.character.luck }}
                </dd>
                <dt class="text-sm font-semibold leading-6 text-gray-600">Luck</dt>
            </div>
            <div class="flex flex-col bg-gray-400/5 p-8">
                <dd class="font-semibold tracking-tight text-cthulhu-green-800 text-5xl" @click="openMagicPointsModal">
                    {{ prop.character.magic_points }}
                </dd>
                <dt class="text-sm font-semibold leading-6 text-gray-600">Magic points</dt>
            </div>
        </dl>

        <Modal :show="modalShow" @close="closeModal" max-width="sm">
            <div class="bg-cthulhu-green-200 p-6 border-cthulhu-green-100 align-bottom">
                <div class="flex flex-col h-full gap-1">
                    <label
                        for="starting_value"
                        class="block text-md font-medium leading-6 text-gray-900"
                    >
                        {{ modalAttributes.title }}
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
                            @click="saveVitals"
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
