<script setup>
import {EnvelopeIcon, CubeIcon} from "@heroicons/vue/20/solid/index.js";
import Badge from "@/Pages/Components/Badge.vue";
defineProps({player: Object})
</script>

<template>
    <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="truncate text-sm font-medium text-gray-900">{{ player.name }}</h3>
                <badge :badge-class="{ 'text-xs': true }">{{ player.role }}</badge>
            </div>
        </div>
        <img v-if="player.imageUrl" class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300" :src="player.imageUrl" alt=""/>
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            <div class="flex w-0 flex-1">
                <a :href="`mailto:${player.email}`"
                   class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
                    <EnvelopeIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    Email
                </a>
            </div>
            <div class="-ml-px flex w-0 flex-1">
                <a
                   class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
                    <CubeIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    Call
                </a>
            </div>
        </div>
    </div>
</template>
