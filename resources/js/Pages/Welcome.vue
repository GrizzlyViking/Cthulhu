<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome"/>

    <div class="relative isolate flex min-h-screen items-center justify-center overflow-hidden bg-cthulhu-green-950 px-4">
        <img
            src="/images/cthulhu_man_reading.jpeg"
            alt=""
            class="absolute inset-0 -z-20 size-full object-cover"
        />
        <div
            class="absolute inset-0 -z-10 bg-gradient-to-t from-cthulhu-green-950 via-cthulhu-green-950/85 to-cthulhu-green-950/60"
            aria-hidden="true"
        ></div>

        <div class="mx-auto max-w-2xl py-24 text-center sm:py-32">
            <p class="eyebrow-on-dark">A Call of Cthulhu campaign</p>
            <h1 class="display mt-3 text-4xl text-parchment-100 sm:text-6xl">Ages of Madness</h1>
            <p class="mt-5 text-lg leading-8 text-cthulhu-green-200">
                Online character sheets for our investigators.
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <Link v-if="$page.props.auth.user" :href="route('home')" class="btn-primary">
                    Enter
                </Link>
                <Link v-else href="/login" class="btn-primary">Log in</Link>
            </div>
        </div>
    </div>
</template>
