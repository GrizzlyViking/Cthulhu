<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {debounce} from "lodash";
import {useAdjustAttribute} from "@/Pages/Composables/useAdjustAttribute.js";
const prop = defineProps({character: Object, editable: Boolean});

const {updateAttribute} = useAdjustAttribute('character')

</script>

<template>
    <div class="p-3 m-3 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-3">
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Strength</div>
            <regular-half-fifth v-model="prop.character.strength" @input="updateAttribute('strength', $event)" :editable="editable"></regular-half-fifth>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Dexterity</div>
            <regular-half-fifth v-model="prop.character.dexterity" @input="updateAttribute('dexterity', $event)" :editable="editable"></regular-half-fifth>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Intelligence</div>
            <regular-half-fifth v-model="prop.character.intelligence" @input="updateAttribute('intelligence', $event)" :editable="editable"></regular-half-fifth>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Constitution</div>
            <regular-half-fifth v-model="prop.character.constitution" @input="updateAttribute('constitution', $event)" :editable="editable"></regular-half-fifth>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Appearance</div>
            <regular-half-fifth v-model="prop.character.appearance" @input="updateAttribute('appearance', $event)" :editable="editable"></regular-half-fifth>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Power</div>
            <regular-half-fifth v-model="prop.character.power" @input="updateAttribute('power', $event)" :editable="editable"></regular-half-fifth>
        </div>

        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Size</div>
            <regular-half-fifth v-model="prop.character.size" @input="updateAttribute('size', $event)" :editable="editable"></regular-half-fifth>
        </div>
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Education</div>
            <regular-half-fifth v-model="prop.character.education" @input="updateAttribute('education', $event)" :editable="editable"></regular-half-fifth>
        </div>
    </div>
</template>

<style scoped>

</style>
