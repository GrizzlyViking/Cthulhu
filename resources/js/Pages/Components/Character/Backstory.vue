<script setup>
import {computed} from "vue";
import {router} from "@inertiajs/vue3";
import {useAdjustAttribute} from "@/Pages/Composables/useAdjustAttribute.js";
import {UserCircleIcon, BookOpenIcon} from "@heroicons/vue/24/solid/index.js";
const {updateAttribute} = useAdjustAttribute('character')

const prop = defineProps({character: Object, editable: Boolean});

const avatarImg = computed(() => {
    if (prop.character.avatar) {
        return '/storage/' + prop.character.avatar
    }
    return '/images/cthulhu_man_reading.jpeg'
})

const renameCharacter = (event) => {
    router.get(route('rename.character', {
        character: prop.character.slug
    }), {
        value: event.target.value
    });
}
</script>

<template>
    <div class="relative isolate overflow-hidden bg-gray-900 py-24 sm:py-32">
        <img :src="avatarImg" alt="avatar" class="absolute inset-0 -z-10 h-full w-full object-cover object-right md:object-center" />
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">
                    <input v-model="prop.character.name"
                           @focusout="renameCharacter"
                           class="text-2xl sm:text-3xl md:text-4xl limelight-regular border-0 bg-transparent m-0 p-0 focus:border-gray-300 w-full"
                           :disabled="!prop.editable"
                    >
                </h2>
            </div>
            <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:mt-20 lg:mx-0 lg:max-w-none md:grid-cols-2 md:gap-8">
                <div class="flex gap-x-4 rounded-xl bg-cthulhu-green-800/80 p-6 ring-1 ring-inset ring-white/10">
                    <component :is="UserCircleIcon" class="h-7 w-5 flex-none text-indigo-400" aria-hidden="true" />
                    <div class="text-base leading-7">
                        <h3 class="font-semibold text-white">Character</h3>
                        <div class="mt-2 text-gray-300">Player: {{ prop.character.player.name }}</div>
                        <div class="mt-2 text-gray-300">
                            Age:
                            <input v-model="prop.character.age"
                                   type="number"
                                   @input="updateAttribute('age', $event)"
                                   class="border-0 bg-transparent m-0 p-0 focus:border-gray-300"
                                   :disabled="!prop.editable"
                            >
                        </div>
                        <div class="mt-2 text-gray-300">
                            Gender:
                            <input v-model="prop.character.gender"
                                   @input="updateAttribute('gender', $event)"
                                   class="border-0 bg-transparent m-0 p-0 focus:border-gray-300"
                                   :disabled="!prop.editable"
                            >
                        </div>
                    </div>
                </div>
                <div class="flex gap-x-4 rounded-xl bg-cthulhu-green-800/80 p-6 ring-1 ring-inset ring-white/10">
                    <component :is="BookOpenIcon" class="h-7 w-5 flex-none text-indigo-400" aria-hidden="true" />
                    <div class="text-base leading-7">
                        <h3 class="font-semibold text-white">Background</h3>
                        <div class="mt-2 text-gray-300">
                            Occupation:
                            <input v-model="prop.character.occupation"
                                   @input="updateAttribute('occupation', $event)"
                                   class="border-0 m-0 p-0 bg-transparent w-full"
                                   :disabled="!prop.editable"
                            >
                        </div>
                        <div class="mt-2 text-gray-300">
                            Residence:
                            <input v-model="prop.character.residence"
                                   @input="updateAttribute('residence', $event)"
                                   class="border-0 m-0 bg-transparent p-0 focus:border-gray-300"
                                   :disabled="!prop.editable"
                            >
                        </div>
                        <div class="mt-2 text-gray-300">
                            Birthplace:
                            <input v-model="prop.character.birthplace"
                                   @input="updateAttribute('birthplace', $event)"
                                   class="border-0 bg-transparent m-0 p-0 focus:border-gray-300"
                                   :disabled="!prop.editable"
                            ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.limelight-regular {
    font-family: "Limelight", sans-serif;
    font-weight: 400;
    font-style: normal;
}
</style>
