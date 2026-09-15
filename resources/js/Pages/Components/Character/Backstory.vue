<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const prop = defineProps({ character: Object, editable: Boolean });

/**
 * The likeness, shown whole in its own frame. Null when the player has not put
 * one on the sheet yet — the masthead is then the backdrop and the name alone,
 * as it was before there were two pictures.
 */
const portraitImg = computed(() =>
    prop.character.avatar ? '/storage/' + prop.character.avatar : null
);

/**
 * The scene behind the name, cropped to whatever room the masthead has.
 *
 * With none uploaded this is the house picture — which is what *Use the default*
 * on the sheet's Backdrop card puts a player back to.
 */
const bannerImg = computed(() =>
    prop.character.banner ? '/storage/' + prop.character.banner : '/images/cthulhu_man_reading.jpeg'
);

/*
 * The line above the name says what this investigator does, because on a sheet
 * full of investigators that is the useful half. Only when there is no
 * occupation yet does it fall back to saying what they are.
 */
const eyebrow = computed(() => prop.character.occupation?.trim() || 'Investigator');

/*
 * The name is a textarea rather than an input so that a long one wraps instead
 * of scrolling out of sight — "Bartholomew Ashcroft-Winterbourne" is a name a
 * player will pick, and half of it disappearing off the side is no good. It is
 * still one line of text: Return leaves the field rather than adding a line.
 *
 * It starts a step smaller than the rest of the display type. Beside the
 * portrait a phone leaves the name about two hundred pixels, and at `text-3xl`
 * a word like "Bartholomew" does not fit on a line of its own — so the browser
 * breaks it in the middle, which looks like a fault. A step down and it wraps
 * at the spaces, where a name is meant to break.
 */
const nameField = ref(null);

const fitName = () => {
    const field = nameField.value;

    if (! field) {
        return;
    }

    field.style.height = 'auto';
    field.style.height = `${field.scrollHeight}px`;
};

let watcher = null;

onMounted(() => {
    fitName();

    /*
     * The name re-wraps whenever the column it sits in changes width — a phone
     * turned on its side, the portrait appearing beside it. Only width is worth
     * reacting to: height is what this callback sets, and following that would
     * be a loop.
     */
    let lastWidth = 0;

    watcher = new ResizeObserver((entries) => {
        const width = entries[0].contentRect.width;

        if (width !== lastWidth) {
            lastWidth = width;
            fitName();
        }
    });

    watcher.observe(nameField.value);
});

onBeforeUnmount(() => watcher?.disconnect());

watch(() => prop.character.name, () => nextTick(fitName));

/**
 * Renaming re-slugs the character, so this is a full Inertia visit rather than an
 * axios call — the redirect carries the page to the new URL.
 */
const renameCharacter = (event) => {
    // A pasted name can carry line breaks the field would keep. It is one line.
    const name = event.target.value.replace(/\s+/g, ' ').trim();

    if (! prop.editable || name === '' || name === prop.character.name) {
        event.target.value = prop.character.name;
        nextTick(fitName);

        return;
    }

    router.put(route('character.rename', { character: prop.character.slug }), {
        value: name,
    }, { preserveScroll: true });
};
</script>

<template>
    <section class="relative isolate overflow-hidden rounded-lg shadow-raised ring-1 ring-cthulhu-green-900/40">
        <img
            :src="bannerImg"
            alt=""
            class="absolute inset-0 -z-20 size-full object-cover object-center"
        />
        <!-- Scrim keeps the type legible whatever picture is behind it. -->
        <div
            class="absolute inset-0 -z-10 bg-gradient-to-t from-cthulhu-green-950 via-cthulhu-green-950/85 to-cthulhu-green-950/40"
            aria-hidden="true"
        ></div>

        <!-- The masthead is the name and the face, and nothing else: everything
             that was once alongside them reads better on the tabs below. -->
        <div class="flex min-h-48 items-end gap-4 p-5 sm:min-h-64 sm:gap-8 sm:p-7">
            <div class="min-w-0 grow">
                <p class="eyebrow-on-dark">{{ eyebrow }}</p>
                <textarea
                    ref="nameField"
                    :value="prop.character.name"
                    rows="1"
                    aria-label="Character name"
                    class="display mt-1 block w-full min-w-0 resize-none overflow-hidden border-0 bg-transparent p-0 text-2xl leading-tight text-parchment-100 sm:text-4xl lg:text-5xl"
                    :class="prop.editable ? 'field-inline' : 'ring-0 focus:ring-0'"
                    :disabled="!prop.editable"
                    @input="fitName"
                    @keydown.enter.prevent="$event.target.blur()"
                    @focusout="renameCharacter"
                ></textarea>
                <!-- The Keeper's own cast has no player, and only ever
                     reaches this sheet in the Keeper's own hands. -->
                <p class="mt-2 text-sm text-cthulhu-green-200">
                    <template v-if="prop.character.player">
                        Played by {{ prop.character.player.name }}
                    </template>
                    <template v-else> Nobody's investigator — one of the Keeper's own. </template>
                </p>
            </div>

            <!--
                A third of the masthead, capped so the portrait does not push play below the fold. The picture fills the frame rather than
                being mounted inside it, so a likeness that is not quite 3:4 loses
                a little off its edges instead of sitting in bars — and it is
                cropped from the bottom (`object-top`), because a face is at the
                top of a portrait and the coat is what can be spared.

                The brass ring is deliberately *not* `ring-inset`: an inset ring
                is painted with the frame's own box decorations, which puts it
                under the picture rather than around it.
            -->
            <div
                v-if="portraitImg"
                class="aspect-[3/4] w-1/3 max-w-40 shrink-0 overflow-hidden rounded-md bg-cthulhu-green-900/70 shadow-raised ring-1 ring-cthulhu-yellow-600/60 backdrop-blur-sm"
            >
                <img :src="portraitImg" :alt="prop.character.name" class="size-full object-cover object-top" />
            </div>
        </div>
    </section>
</template>
