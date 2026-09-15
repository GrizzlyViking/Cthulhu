<script setup>
import CharacterName from '@/Components/CharacterName.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    sections: { type: Array, required: true },
    mobile: Boolean,
    allowCreate: Boolean,
});
</script>

<template>
    <div class="flex flex-col gap-2">
        <template v-for="section in sections" :key="section.label">
            <div v-if="section.characters.length">
                <p class="px-4 pb-1 pt-2" :class="mobile ? 'eyebrow-on-dark' : 'eyebrow'">{{ section.label }}</p>
                <component
                    :is="mobile ? ResponsiveNavLink : DropdownLink"
                    v-for="character in section.characters"
                    :key="character.slug"
                    :href="route('character.show', { character: character.slug })"
                >
                    <CharacterName :character="character" />
                    <span v-if="section.detail?.(character)" class="block text-xs" :class="mobile ? 'text-cthulhu-green-200' : 'text-cthulhu-green-500'">
                        {{ section.detail(character) }}
                    </span>
                </component>
            </div>
        </template>
        <p v-if="allowCreate && !sections.some(section => section.characters.length)" class="px-4 py-2 text-sm" :class="mobile ? 'text-cthulhu-green-200' : 'text-cthulhu-green-500'">
            No investigators in the current campaign.
        </p>
        <component :is="mobile ? ResponsiveNavLink : DropdownLink" v-if="allowCreate" :href="route('character.create')">
            + Create investigator
        </component>
    </div>
</template>
