<script setup>
import {useAdjustAttribute} from "@/Pages/Composables/useAdjustAttribute.js";
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {computed} from "vue";
const {updateAttribute} = useAdjustAttribute('character')

const prop = defineProps({character: Object, editable: Boolean});

const damageBonus = computed(() => {
    let strAndSiz = Number(prop.character.strength) + Number(prop.character.size);

    if (strAndSiz < 65) {
        return '-2';
    }
    if (strAndSiz > 64 && strAndSiz < 85) {
        return '-1';
    }
    if (strAndSiz > 84 && strAndSiz < 125) {
        return 'none';
    }
    if (strAndSiz > 124 && strAndSiz < 164) {
        return '+1D4';
    }
    if (strAndSiz > 163 && strAndSiz < 204) {
        return '+1D6';
    }
})

const build = computed(() => {
    let strAndSiz = Number(prop.character.strength) + Number(prop.character.size);

    if (strAndSiz < 65) {
        return '-2';
    }
    if (strAndSiz > 64 && strAndSiz < 85) {
        return '-1';
    }
    if (strAndSiz > 84 && strAndSiz < 125) {
        return 'none';
    }
    if (strAndSiz > 124 && strAndSiz < 164) {
        return '+1';
    }
    if (strAndSiz > 163 && strAndSiz < 204) {
        return '+2';
    }
})

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

const renameCharacter = (event) => {
    axios.get(route('rename.character', {
        character: prop.character.slug,
        value: event.target.value
    }));
}
</script>

<template>
    <div class="justify-between">
        <div class="flex justify-between">
            <span class="text-gray-400">Name:</span>
            <span>
            <input v-model="prop.character.name"
                   @focusout="renameCharacter($event)"
                   class="border-0 m-0 p-0 text-right focus:border-gray-300"
                   :disabled="!editable"
            >
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Player:</span>
            <span>{{ prop.character.player.name }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">Occupation:</span>
            <span class="text-right w-full">
            <input v-model="prop.character.occupation"
                   @input="updateAttribute('occupation', $event)"
                   class="border-0 m-0 p-0 text-right focus:border-gray-300 w-full"
                   :disabled="!editable"
            >
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Age:</span> <span>
        <input v-model="prop.character.age"
               @input="updateAttribute('age', $event)"
               class="border-0 m-0 p-0 text-right focus:border-gray-300"
               :disabled="!editable"
        ></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Gender:</span> <span>
        <input v-model="prop.character.gender"
               @input="updateAttribute('gender', $event)"
               class="border-0 m-0 p-0 text-right focus:border-gray-300"
               :disabled="!editable"
        >
        </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Residence:</span>
            <span>
            <input v-model="prop.character.residence"
                   @input="updateAttribute('residence', $event)"
                   class="border-0 m-0 p-0 text-right focus:border-gray-300"
                   :disabled="!editable"
            >
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Birthplace:</span>
            <span>
            <input v-model="prop.character.birthplace"
                   @input="updateAttribute('birthplace', $event)"
                   class="border-0 m-0 p-0 text-right focus:border-gray-300"
                   :disabled="!editable"
            >
            </span>
        </div>
    </div>
    <div>
        <div class="flex justify-between h-7">
            <span class="text-gray-400">Damage bonus:</span>
            <span>{{ damageBonus }}</span>
        </div>
        <div class="flex justify-between h-7">
            <span class="text-gray-400">Build:</span>
            <span>{{ build }}</span>
        </div>
        <div class="flex justify-between h-7">
            <span class="text-gray-400">Move Rate: ({{ moveRate * 5 }} meter/round)</span>
            <span>{{ moveRate }}</span>
        </div>
        <div class="flex justify-between h-7">
            <span class="text-gray-400 my-auto">Dodge:</span>
            <regular-half-fifth
                v-model="prop.character.dodge"
                @input="updateAttribute('dodge', $event)"
                :editable="editable"
            ></regular-half-fifth>
        </div>
    </div>
</template>

<style scoped>

</style>
