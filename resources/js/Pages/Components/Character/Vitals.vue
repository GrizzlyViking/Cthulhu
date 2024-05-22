<script setup>
import {debounce} from "lodash";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";
import {ref} from "vue";

const prop = defineProps({
    character: Object,
    editable: Boolean
});

const isHitPointsModalOpen = ref(false);
const isSanityModalOpen = ref(false);

let adjustHitPoints = debounce((event) => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'hit_points',
        value: event.target.value
    }));
    if (Math.floor(prop.character.hit_points / 2) > Number(event.target.value)) {
        openHitPointsModal();
    }
    prop.character.hit_points = event.target.value;
}, 600)

let adjustLuck = debounce(() => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'luck',
        value: prop.character.luck
    }));
}, 600);

let adjustMagicPoints = debounce(() => {
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'magic_points',
        value: prop.character.magic_points
    }));
}, 600);

let adjustSanity = debounce((event) => {
    if ((Number(prop.character.sanity) - 5) > Number(event.target.value)) {
        openSanityModal();
    }
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'sanity',
        value: event.target.value
    }));
    prop.character.sanity = event.target.value;
}, 600)

let unconscious = (setValue) => {
    prop.character.unconscious = setValue;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'unconscious',
        value: prop.character.unconscious
    }));
    closeModal()
}

let temporaryInsanity = (setValue) => {
    prop.character.temporary_insanity = setValue;
    axios.put(route('attribute.update', {
        character: prop.character.slug,
        attribute: 'temporary_insanity',
        value: setValue
    }));
    console.log('temporary_insanity');
    closeModal();
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
    <dl class="grid grid-cols-1 gap-x-8 gap-y-8 text-center lg:grid-cols-4">
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
            <dt class="text-base leading-7 text-gray-600">
                Hit Points
                <div class="text-gray-400 text-xs">Max hit points:
                    {{ Math.floor((Number(character.constitution) + Number(character.size)) / 5) }}
                </div>
            </dt>
            <input :value="prop.character.hit_points" @input="adjustHitPoints($event)" type="text"
                   class="transition border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight"
            >
            <div
                @click="unconscious(!prop.character.unconscious)"
                class="h-8 order-last border border-gray-200 rounded-lg px-2 p-1"
                :class="{
                                    'bg-red-300': prop.character.unconscious
                                }"
            >
                Unconscious
            </div>
        </div>
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
            <dt class="text-base leading-7 text-gray-600">
                Sanity
                <div class="text-gray-400 text-xs">Starting sanity: {{ prop.character.power }}</div>
            </dt>
            <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                <input :value="prop.character.sanity"
                       @input="adjustSanity($event)"
                       class="border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight"
                       type="text">
            </dd>
            <div
                @click="temporaryInsanity(!prop.character.temporary_insanity)"
                class="h-8 order-last border border-gray-200 rounded-lg px-2 p-1"
                :class="{
                                    'bg-red-300': prop.character.temporary_insanity
                                }"
            >
                Temporary Insanity
            </div>
        </div>
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
            <dt class="text-base leading-7 text-gray-600">Luck</dt>
            <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                <input v-model="prop.character.luck" type="text" @input="adjustLuck"
                       class="border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
            </dd>
        </div>
        <div class="mx-auto flex max-w-xs flex-col gap-y-4">
            <dt class="text-base leading-7 text-gray-600">
                Magic points
                <div class="text-gray-400 text-xs">Max magic points:
                    {{ Math.floor(Number(prop.character.power) / 5) }}
                </div>
            </dt>
            <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                <input v-model="prop.character.magic_points" type="text" @input="adjustMagicPoints"
                       class="border-0 p-0 m-0 ml-2 w-20 text-center order-first text-5xl font-semibold tracking-tight">
            </dd>
        </div>
    </dl>
    <modal-popup :is-open="isHitPointsModalOpen"
                 @modal-close="closeModal"
                 @response1="closeModal"
                 @response2="unconscious(1)"
    >
        <template #title>Lots of damage!?!</template>
        <template #default>
            You have take more than half of you present hit points. You therefore need to pass a test against your
            constitution ({{ prop.character.constitution }}). If you fail you will fall unconscious.
        </template>
        <template #response1>I passed my constitution test</template>
        <template #response2>I failed my constitution test</template>
    </modal-popup>

    <modal-popup :is-open="isSanityModalOpen"
                 @modal-close="closeModal"
                 @response1="closeModal"
                 @response2="temporaryInsanity(true)"
    >
        <template #title>Insane in the membrane</template>
        <template #default>
            If an investigator loses 5 or more Sanity points as the consequence of a single Sanity roll, they have
            suffered major emotional trauma. The player must roll 1D100. If the result is equal to or less than their
            Intelligence ({{ prop.character.intelligence }}), the investigator fully understands and comprehends what
            has been seen and goes temporarily insane (for 1D10 hours). If they fail the roll, their mind is closed to
            the horror and they remain sane (for now).
        </template>
        <template #response1>I passed</template>
        <template #response2>I'm mad as a box of frogs.</template>
    </modal-popup>
</template>

<style scoped>

</style>
