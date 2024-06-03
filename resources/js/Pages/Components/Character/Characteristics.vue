<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {useAdjustAttribute} from "@/Pages/Composables/useAdjustAttribute.js";
import {computed} from "vue";
const prop = defineProps({character: Object, editable: Boolean});
const {updateAttribute} = useAdjustAttribute('character')

const moveRate = computed(() => {
    let move = '8';
    if (prop.character.dexterity < prop.character.size && prop.character.strength < prop.character.size) {
        move = '7'
    }
    if (prop.character.dexterity > prop.character.size && prop.character.strength > prop.character.size) {
        move = '9'
    }

    if (prop.character.age > 40 && prop.character.age < 51) {
        return move - 1
    }
    if (prop.character.age > 50 && prop.character.age < 61) {
        return move - 2
    }
    if (prop.character.age > 60 && prop.character.age < 71) {
        return move - 3
    }
    if (prop.character.age > 70 && prop.character.age < 81) {
        return move - 4
    }
    if (prop.character.age > 80 && prop.character.age < 91) {
        return move - 5
    }

    return move
})

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
        <div class="grid grid-cols-2 justify-between">
            <div class="font-bold p-2 align-middle text-right">Move Rate</div>
            <div class="text-center py-3 flex-initial w-12 text-gray-900 border border-cthulhu-green-400 rounded-lg bg-gray-50 text-xs read-only:bg-gray-100">{{ moveRate }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>
