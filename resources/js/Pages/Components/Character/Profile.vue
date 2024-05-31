<script setup>
import {useAdjustAttribute} from "@/Pages/Composables/useAdjustAttribute.js";
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {computed} from "vue";
import {UserCircleIcon} from "@heroicons/vue/24/solid/index.js";
import {router} from "@inertiajs/vue3";

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

const avatarImg = computed(() => {
    return '/storage/' + prop.character.avatar
})

const renameCharacter = (event) => {
    router.get(route('rename.character', {
        character: prop.character.slug
    }), {
        value: event.target.value
    });
}
const handleFileUpload = async (event) => {
    const formData = new FormData();
    formData.append('avatar', event.target.files[0]);
    axios.post(route('character.upload.avatar', {
        character: prop.character.slug
    }), formData, {
        headers: {
            'content-type': 'multipart/form-data',
        }
    }).then((response) => {
        console.log(response)
    }).catch((error) => {
        console.error(error)
    })
}
</script>

<template>
    <div class="justify-between">
        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Name:</span>
            <span class="w-full">
            <input v-model="prop.character.name"
                   @focusout="renameCharacter($event)"
                   class="border-0 bg-cthulhu-green-200 m-0 p-0 text-right focus:border-gray-300 w-full"
                   :disabled="!editable"
            >
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Player:</span>
            <span>{{ prop.character.player.name }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Occupation:</span>
            <span class="text-right w-full">
            <input v-model="prop.character.occupation"
                   @input="updateAttribute('occupation', $event)"
                   class="border-0 bg-cthulhu-green-200 m-0 p-0 text-right focus:border-gray-300 w-full"
                   :disabled="!editable"
            >
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Age:</span> <span>
        <input v-model="prop.character.age"
               @input="updateAttribute('age', $event)"
               class="border-0 bg-cthulhu-green-200 m-0 p-0 text-right focus:border-gray-300"
               :disabled="!editable"
        ></span>
        </div>
        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Gender:</span> <span>
        <input v-model="prop.character.gender"
               @input="updateAttribute('gender', $event)"
               class="border-0 bg-cthulhu-green-200 m-0 p-0 text-right focus:border-gray-300"
               :disabled="!editable"
        >
        </span>
        </div>
        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Residence:</span>
            <span>
            <input v-model="prop.character.residence"
                   @input="updateAttribute('residence', $event)"
                   class="border-0 m-0 bg-cthulhu-green-200 p-0 text-right focus:border-gray-300"
                   :disabled="!editable"
            >
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-cthulhu-green-800">Birthplace:</span>
            <span>
            <input v-model="prop.character.birthplace"
                   @input="updateAttribute('birthplace', $event)"
                   class="border-0 bg-cthulhu-green-200 m-0 p-0 text-right focus:border-gray-300"
                   :disabled="!editable"
            >
            </span>
        </div>


        <div class="mt-7">
            <div class="flex justify-between h-7">
                <span class="text-cthulhu-green-800">Damage bonus:</span>
                <span>{{ damageBonus }}</span>
            </div>
            <div class="flex justify-between h-7">
                <span class="text-cthulhu-green-800">Build:</span>
                <span>{{ build }}</span>
            </div>
            <div class="flex justify-between h-7">
                <span class="text-cthulhu-green-800">Move Rate: ({{ moveRate * 5 }} meter/round)</span>
                <span>{{ moveRate }}</span>
            </div>
            <div class="flex justify-between h-7">
                <span class="text-cthulhu-green-800 my-auto">Dodge:</span>
                <regular-half-fifth
                    v-model="prop.character.dodge"
                    @input="updateAttribute('dodge', $event)"
                    :editable="editable"
                ></regular-half-fifth>
            </div>
        </div>
    </div>
    <div>
        <div v-if="!editable && prop.character.avatar">
            <img class="h-auto max-w-xs ms-auto" :src="avatarImg" alt="image description">
        </div>
        <div v-else class="col-span-full">
            <label for="photo" class="block text-sm font-medium leading-6 text-gray-900">Photo</label>
            <div class="mt-2 flex items-center gap-x-3">
                <UserCircleIcon class="h-12 w-12 text-gray-300" aria-hidden="true" />
                <input type="file" v-on:change="handleFileUpload" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
