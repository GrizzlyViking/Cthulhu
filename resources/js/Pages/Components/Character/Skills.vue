<script setup>
import RegularHalfFifth from "@/Pages/Components/RegularHalfFifth.vue";
import {debounce} from "lodash";

const prop = defineProps({character: Object, editable: Boolean});

let adjustSkill = debounce((skill, event) => {
    axios.put(route('character.skill.update', {
        character: prop.character.slug,
        skill: skill.slug,
        value: event.target.value
    }));
}, 600);

let incrementExperience = (skill) => {
    axios.get(route('experience.increment', {
        character: prop.character.slug,
        skill: skill.slug
    }));
    skill.pivot.experience = skill.pivot.experience + 1;
}
let resetExperience = (skill) => {
    axios.get(route('experience.reset', {
        character: prop.character.slug,
        skill: skill.slug
    }));
    skill.pivot.experience = 0;
}

</script>

<template>
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6 m-3 grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-3">
            <div v-for="skill in prop.character.skills" :key="skill.id"
                 class="grid grid-cols-2 justify-between overflow-clip">

                <div
                    class="font-bold p-2 align-middle text-right"
                ><span @click="incrementExperience(skill)">{{
                        skill.display_name
                    }}</span>
                    <div v-if="skill.pivot.experience > 0" class="ml-1 inline-block"
                         @click="resetExperience(skill)">
                        <div
                            class="align-middle text-center rounded-full border w-6 h-6"
                            :class="{
                                            'border-gray-200': (Math.floor(skill.pivot.value/10) > skill.pivot.experience),
                                            'border-red-800 bg-red-800 colour text-color-white': (Math.floor(skill.pivot.value/10) <= skill.pivot.experience)
                                        }"
                        >
                            {{ skill.pivot.experience }}

                        </div>
                    </div>
                </div>

                <regular-half-fifth v-model="skill.pivot.value"
                                    @input="adjustSkill(skill)"
                                    :editable="editable"
                ></regular-half-fifth>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
