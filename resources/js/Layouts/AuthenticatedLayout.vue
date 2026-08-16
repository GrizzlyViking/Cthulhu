<script setup>
import { computed, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import FlashMessages from '@/Components/FlashMessages.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Bars3Icon, ChevronDownIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { useRoles } from '@/Pages/Composables/useRoles.js';

const showingNavigationDropdown = ref(false);

const { isAdmin, isKeeper } = useRoles();

const page = usePage();

const inPlay = (character) => character.in_active_game === true;
const gameless = (character) => (character.games ?? []).length === 0;

/*
 * The nav leads with the campaign the group is playing. Investigators from
 * finished campaigns move to "Previous games" rather than disappearing, and so
 * do any that are in no game at all — a fresh draft made while the group had
 * none, or one whose game was deleted.
 */
const characters = computed(() => {
    const own = page.props.auth.characters.own ?? [];
    const others = page.props.auth.characters.others ?? [];
    const everyone = [...own, ...others];

    return {
        own: own.filter(inPlay),
        others: others.filter(inPlay),
        previous: everyone.filter((character) => !inPlay(character) && !gameless(character)),
        unassigned: everyone.filter(gameless),
    };
});

const hasPreviousGames = computed(
    () => characters.value.previous.length > 0 || characters.value.unassigned.length > 0
);

/** The most recent game an investigator was played in, to label them by. */
const lastGameName = (character) =>
    [...(character.games ?? [])].sort((a, b) => b.id - a.id)[0]?.name ?? null;
</script>

<template>
    <div>
        <div class="min-h-screen bg-cthulhu-green-950">
            <nav class="border-b border-cthulhu-green-900 bg-cthulhu-green-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex items-center gap-2">
                            <Link :href="route('dashboard')" class="shrink-0 rounded-md p-1 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-cthulhu-yellow-400" />
                            </Link>

                            <!-- Primary navigation -->
                            <div class="hidden items-center gap-1 sm:ms-6 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('calendar', { calendar: 'ages-of-madness' })" :active="route().current('calendar')">
                                    Calendar
                                </NavLink>
                                <NavLink v-if="isKeeper" :href="route('keeper.index')" :active="route().current('keeper.*')">
                                    Keeper
                                </NavLink>
                                <NavLink v-if="isAdmin" :href="route('admin.index')" :active="route().current('admin.*')">
                                    Admin
                                </NavLink>

                                <Dropdown align="left" width="60">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium text-cthulhu-green-200 transition hover:bg-cthulhu-green-800 hover:text-parchment-100 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
                                            Characters
                                            <ChevronDownIcon class="size-4" aria-hidden="true" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <p class="px-4 pb-1 pt-2 eyebrow">Your investigators</p>
                                        <DropdownLink
                                            v-for="character in characters.own"
                                            :key="character.slug"
                                            :href="route('character.show', { character: character.slug })"
                                        >
                                            {{ character.name }}
                                        </DropdownLink>

                                        <template v-if="characters.others.length">
                                            <p class="px-4 pb-1 pt-3 eyebrow">Other investigators</p>
                                            <DropdownLink
                                                v-for="character in characters.others"
                                                :key="character.slug"
                                                :href="route('character.show', { character: character.slug })"
                                            >
                                                {{ character.name }}
                                                <span class="text-cthulhu-green-500">— {{ character.player.name }}</span>
                                            </DropdownLink>
                                        </template>

                                        <div class="my-1 divider"></div>
                                        <DropdownLink :href="route('character.create')">+ Create new character</DropdownLink>
                                    </template>
                                </Dropdown>

                                <!-- Investigators from campaigns that are over, and any in no game at all. -->
                                <Dropdown v-if="hasPreviousGames" align="left" width="60">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium text-cthulhu-green-200 transition hover:bg-cthulhu-green-800 hover:text-parchment-100 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
                                            Previous games
                                            <ChevronDownIcon class="size-4" aria-hidden="true" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <template v-if="characters.previous.length">
                                            <p class="px-4 pb-1 pt-2 eyebrow">Finished campaigns</p>
                                            <DropdownLink
                                                v-for="character in characters.previous"
                                                :key="character.slug"
                                                :href="route('character.show', { character: character.slug })"
                                            >
                                                {{ character.name }}
                                                <span class="text-cthulhu-green-500">— {{ lastGameName(character) }}</span>
                                            </DropdownLink>
                                        </template>

                                        <template v-if="characters.unassigned.length">
                                            <p class="px-4 pb-1 pt-3 eyebrow">Not in a game</p>
                                            <DropdownLink
                                                v-for="character in characters.unassigned"
                                                :key="character.slug"
                                                :href="route('character.show', { character: character.slug })"
                                            >
                                                {{ character.name }}
                                            </DropdownLink>
                                        </template>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Account -->
                        <div class="hidden items-center sm:flex">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium text-cthulhu-green-200 transition hover:bg-cthulhu-green-800 hover:text-parchment-100 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
                                        {{ $page.props.auth.user.name }}
                                        <ChevronDownIcon class="size-4" aria-hidden="true" />
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                    <DropdownLink :href="route('faq')">FAQ</DropdownLink>
                                    <div class="my-1 divider"></div>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Log out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                type="button"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                :aria-expanded="showingNavigationDropdown"
                                aria-label="Toggle navigation"
                                class="inline-flex items-center justify-center rounded-md p-2 text-cthulhu-green-200 transition hover:bg-cthulhu-green-800 hover:text-parchment-100 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500"
                            >
                                <XMarkIcon v-if="showingNavigationDropdown" class="size-6" aria-hidden="true" />
                                <Bars3Icon v-else class="size-6" aria-hidden="true" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div v-show="showingNavigationDropdown" class="border-t border-cthulhu-green-800 sm:hidden">
                    <div class="space-y-1 py-3">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('calendar', { calendar: 'ages-of-madness' })" :active="route().current('calendar')">Calendar</ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isKeeper" :href="route('keeper.index')" :active="route().current('keeper.*')">
                            Keeper
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin" :href="route('admin.index')" :active="route().current('admin.*')">
                            Admin
                        </ResponsiveNavLink>
                    </div>

                    <div class="border-t border-cthulhu-green-800 py-3">
                        <p class="px-4 pb-1 eyebrow-on-dark">Investigators</p>
                        <div class="space-y-1">
                            <ResponsiveNavLink
                                v-for="character in characters.own"
                                :key="character.slug"
                                :href="route('character.show', { character: character.slug })"
                            >
                                {{ character.name }}
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-for="character in characters.others"
                                :key="character.slug"
                                :href="route('character.show', { character: character.slug })"
                            >
                                {{ character.name }}
                                <span class="text-cthulhu-green-400">— {{ character.player.name }}</span>
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('character.create')">+ Create character</ResponsiveNavLink>
                        </div>

                        <template v-if="hasPreviousGames">
                            <p class="px-4 pb-1 pt-3 eyebrow-on-dark">Previous games</p>
                            <div class="space-y-1">
                                <ResponsiveNavLink
                                    v-for="character in characters.previous"
                                    :key="character.slug"
                                    :href="route('character.show', { character: character.slug })"
                                >
                                    {{ character.name }}
                                    <span class="text-cthulhu-green-400">— {{ lastGameName(character) }}</span>
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-for="character in characters.unassigned"
                                    :key="character.slug"
                                    :href="route('character.show', { character: character.slug })"
                                >
                                    {{ character.name }}
                                    <span class="text-cthulhu-green-400">— not in a game</span>
                                </ResponsiveNavLink>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-cthulhu-green-800 py-3">
                        <div class="px-4 pb-2">
                            <div class="text-base font-medium text-parchment-100">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm text-cthulhu-green-300">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="space-y-1">
                            <ResponsiveNavLink :href="route('faq')">FAQ</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page heading -->
            <header v-if="$slots.header" class="border-b border-cthulhu-green-900/60 bg-cthulhu-green-900/60">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Result of the last action, wherever the player lands -->
            <div class="mx-auto max-w-7xl px-4 empty:hidden sm:px-6 lg:px-8 [&>*]:mt-5">
                <FlashMessages />
            </div>

            <!-- Page content. Pages own their own container width. -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
