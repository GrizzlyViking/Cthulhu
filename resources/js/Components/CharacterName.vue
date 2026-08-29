<script setup>
/*
 * An investigator's name wherever they are listed.
 *
 * A deleted sheet is kept rather than destroyed, so it stays in the nav with
 * its name struck through — that is how a player finds one again and restores
 * it. The strikethrough is the whole of the signal on screen, so it carries a
 * word for anyone reading with their ears.
 */
defineProps({
    character: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <span>
        <span
            :class="{
                'line-through decoration-cthulhu-blood-300 decoration-2':
                    character.is_deleted,
            }"
        >
            {{ character.name }}
        </span>
        <span v-if="character.is_deleted" class="sr-only"> — deleted</span>
    </span>
</template>
