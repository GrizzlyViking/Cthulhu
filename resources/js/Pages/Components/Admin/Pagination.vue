<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    // A Laravel paginator, as Inertia serialises it.
    paginator: { type: Object, required: true },
});
</script>

<template>
    <nav
        v-if="paginator.last_page > 1"
        class="flex flex-wrap items-center justify-between gap-3 pt-4"
        aria-label="Pagination"
    >
        <p class="text-xs text-cthulhu-green-500">
            Showing <span class="tabular">{{ paginator.from }}</span>–<span class="tabular">{{ paginator.to }}</span>
            of <span class="tabular">{{ paginator.total }}</span>
        </p>

        <div class="flex flex-wrap items-center gap-1">
            <template v-for="(link, index) in paginator.links" :key="index">
                <span
                    v-if="link.url === null"
                    class="px-2.5 py-1.5 text-xs text-cthulhu-green-500/60"
                    v-html="link.label"
                ></span>
                <Link
                    v-else
                    :href="link.url"
                    preserve-scroll
                    class="rounded-md px-2.5 py-1.5 text-xs font-semibold transition"
                    :class="link.active
                        ? 'bg-cthulhu-green-800 text-parchment-100'
                        : 'text-cthulhu-green-800 hover:bg-cthulhu-green-900/10'"
                    v-html="link.label"
                ></Link>
            </template>
        </div>
    </nav>
</template>
