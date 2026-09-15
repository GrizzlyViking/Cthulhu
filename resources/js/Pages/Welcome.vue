<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ArrowRightIcon } from '@heroicons/vue/20/solid';
</script>

<template>
    <Head title="Welcome" />

    <div class="app-canvas relative isolate min-h-screen overflow-hidden">
        <img src="/images/cthulhu_man_reading.jpeg" alt="" class="absolute inset-0 -z-20 size-full object-cover object-[65%_center]" />
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-cthulhu-green-950 via-cthulhu-green-950/90 to-cthulhu-green-950/60" aria-hidden="true"></div>

        <header class="mx-auto flex max-w-7xl items-center justify-between gap-4 border-b border-parchment-100/15 px-6 py-6 sm:px-10">
            <div class="flex items-center gap-3">
                <ApplicationLogo class="size-10 fill-current text-cthulhu-yellow-400" aria-hidden="true" />
                <span class="eyebrow-on-dark">A Call of Cthulhu campaign</span>
            </div>
            <span class="hidden text-xs uppercase tracking-[0.2em] text-cthulhu-green-200 sm:block">Investigator archive</span>
        </header>

        <main class="mx-auto grid min-h-[calc(100svh-100px)] max-w-7xl items-center gap-12 px-6 py-16 sm:px-10 sm:py-24 lg:grid-cols-[1.25fr_0.75fr]">
            <div>
                <p class="mb-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-cthulhu-yellow-400">
                    <span class="h-px w-10 bg-cthulhu-yellow-500" aria-hidden="true"></span>
                    The evidence stays with you
                </p>
                <h1 class="display max-w-xl text-5xl leading-[1.08] text-parchment-100 sm:text-7xl">Ages of<br />Madness</h1>
                <p class="mt-6 max-w-md text-lg leading-relaxed text-cthulhu-green-200">
                    Every investigator has a story. Keep their skills, scars, and discoveries close at hand.
                </p>
                <Link :href="$page.props.auth.user ? route('home') : route('login')" class="btn-secondary mt-8">
                    {{ $page.props.auth.user ? 'Return to the table' : 'Log in to your sheets' }}
                    <ArrowRightIcon class="size-4" aria-hidden="true" />
                </Link>
                <p class="mt-4 text-xs text-cthulhu-green-200">New to the campaign? Ask someone at the table for an invitation.</p>
            </div>

            <aside class="border-l border-cthulhu-yellow-500/50 pl-6 lg:self-end lg:pb-2">
                <p class="eyebrow-on-dark">At the table</p>
                <dl class="mt-5 flex flex-col gap-6">
                    <div>
                        <dt class="font-semibold text-parchment-100">Your investigator</dt>
                        <dd class="mt-1 max-w-xs text-sm leading-relaxed text-cthulhu-green-200">Characteristics, skills, and everything they carry, on a sheet built for your phone.</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-parchment-100">The Keeper’s screen</dt>
                        <dd class="mt-1 max-w-xs text-sm leading-relaxed text-cthulhu-green-200">Keep an eye on the party and make the rolls they should never see.</dd>
                    </div>
                </dl>
            </aside>
        </main>
    </div>
</template>
