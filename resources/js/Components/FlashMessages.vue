<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircleIcon, ExclamationTriangleIcon } from '@heroicons/vue/20/solid';

const page = usePage();

const flash = computed(() => page.props.flash ?? {});
</script>

<template>
    <div v-if="flash.success || flash.error" class="flex flex-col gap-2">
        <!-- Result of the last action -->
        <div v-if="flash.success" class="card-marked flex items-start gap-2">
            <CheckCircleIcon class="size-5 shrink-0 text-cthulhu-green-800" aria-hidden="true" />
            <p class="text-sm font-medium text-cthulhu-green-900">{{ flash.success }}</p>
        </div>

        <div
            v-if="flash.error"
            class="flex items-start gap-2 rounded-xl bg-cthulhu-blood-400/15 p-4 ring-1 ring-cthulhu-blood-400/50"
        >
            <ExclamationTriangleIcon class="size-5 shrink-0 text-cthulhu-blood-300" aria-hidden="true" />
            <p class="text-sm font-medium text-parchment-100">{{ flash.error }}</p>
        </div>
    </div>
</template>
