<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRightIcon, UserIcon } from '@heroicons/vue/20/solid';
import CharacterName from '@/Components/CharacterName.vue';

defineProps({ character: { type: Object, required: true } });
</script>

<template>
    <Link :href="character.status === 'draft' && !character.is_deleted ? route('character.create') : route('character.show', { character: character.slug })" class="card group flex min-w-0 items-center gap-4 transition hover:ring-cthulhu-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800">
        <div class="flex h-20 w-16 shrink-0 items-center justify-center overflow-hidden rounded-sm bg-parchment-200 ring-1 ring-parchment-300">
            <img v-if="character.avatar" :src="'/storage/' + character.avatar" alt="" loading="lazy" class="size-full object-cover object-top" />
            <UserIcon v-else class="size-7 text-cthulhu-green-500" aria-hidden="true" />
        </div>
        <div class="min-w-0 flex-1">
            <p class="eyebrow">{{ character.is_deleted ? 'Deleted sheet' : character.status === 'draft' ? 'Unfinished investigator' : character.occupation || 'Investigator' }}</p>
            <h3 class="display mt-1 break-words text-lg leading-snug text-cthulhu-green-900"><CharacterName :character="character" /></h3>
            <p class="mt-2 flex items-center gap-2 text-xs font-semibold text-cthulhu-green-500">
                {{ character.is_deleted ? 'View kept sheet' : character.status === 'draft' ? 'Continue investigator' : 'Open sheet' }}
                <ArrowRightIcon class="size-4 transition group-hover:translate-x-1 motion-reduce:transform-none" aria-hidden="true" />
            </p>
        </div>
    </Link>
</template>
