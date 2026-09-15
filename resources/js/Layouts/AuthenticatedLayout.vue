<script setup>
import { computed, ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import InvestigatorLinks from '@/Components/Navigation/InvestigatorLinks.vue';
import { useInvestigatorNavigation } from '@/Pages/Composables/useInvestigatorNavigation.js';
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

const { currentSections, previousSections } = useInvestigatorNavigation();
const navigation = computed(() => {
    // Ziggy reads the browser URL; the Inertia URL makes this list reactive after a visit.
    page.url;
    return [
    { label: 'Dashboard', href: route('dashboard'), active: route().current('dashboard') },
    { label: 'Resources', href: route('resources.index'), active: route().current('resources.*') },
    { label: 'Calendar', href: route('calendar', { calendar: 'ages-of-madness' }), active: route().current('calendar') },
    ...(isKeeper.value ? [{ label: 'Keeper', href: route('keeper.index'), active: route().current('keeper.*') }] : []),
    ...(isAdmin.value ? [{ label: 'Admin', href: route('admin.index'), active: route().current('admin.*') }] : []),
    ];
});

watch(() => page.url, () => { showingNavigationDropdown.value = false; });
</script>

<template>
    <div>
        <a href="#main-content" class="skip-link">Skip to content</a>
        <div class="app-canvas min-h-screen">
            <nav aria-label="Main navigation" class="border-b border-cthulhu-yellow-500/20 bg-cthulhu-green-900">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex items-center gap-2">
                            <Link aria-label="Dashboard" :href="route('dashboard')" class="shrink-0 rounded-md p-1 focus-visible:outline focus-visible:outline-2 focus-visible:outline-cthulhu-yellow-500">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-cthulhu-yellow-400" />
                            </Link>

                            <!-- Primary navigation -->
                            <div class="hidden items-center gap-1 lg:ms-6 lg:flex">
                                <NavLink v-for="item in navigation" :key="item.label" :href="item.href" :active="item.active">
                                    {{ item.label }}
                                </NavLink>

                                <Dropdown align="left" width="60">
                                    <template #trigger="{ open }">
                                        <button type="button" :aria-expanded="open" class="nav-trigger">
                                            Investigators
                                            <ChevronDownIcon class="size-4" aria-hidden="true" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <InvestigatorLinks :sections="currentSections" allow-create />
                                    </template>
                                </Dropdown>

                                <!-- Investigators from campaigns that are over, and any in no game at all. -->
                                <Dropdown v-if="previousSections.length" align="left" width="60">
                                    <template #trigger="{ open }">
                                        <button type="button" :aria-expanded="open" class="nav-trigger">
                                            Previous games
                                            <ChevronDownIcon class="size-4" aria-hidden="true" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <InvestigatorLinks :sections="previousSections" />
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Account -->
                        <div class="hidden items-center lg:flex">
                            <Dropdown align="right" width="48">
                                <template #trigger="{ open }">
                                    <button type="button" :aria-expanded="open" class="nav-trigger">
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
                        <div class="-me-2 flex items-center lg:hidden">
                            <button
                                type="button"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                :aria-expanded="showingNavigationDropdown"
                                aria-controls="mobile-navigation"
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
                <div id="mobile-navigation" v-show="showingNavigationDropdown" class="border-t border-cthulhu-green-800 lg:hidden">
                    <div class="flex flex-col gap-1 py-3">
                        <ResponsiveNavLink v-for="item in navigation" :key="item.label" :href="item.href" :active="item.active">
                            {{ item.label }}
                        </ResponsiveNavLink>
                    </div>

                    <div class="border-t border-cthulhu-green-800 py-3">
                        <InvestigatorLinks :sections="currentSections" mobile allow-create />
                        <InvestigatorLinks v-if="previousSections.length" :sections="previousSections" mobile />
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
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Result of the last action, wherever the player lands -->
            <div class="mx-auto max-w-7xl px-4 empty:hidden sm:px-6 lg:px-8 [&>*]:mt-5">
                <FlashMessages />
            </div>

            <!-- Page content. Pages own their own container width. -->
            <main id="main-content" tabindex="-1">
                <slot />
            </main>
        </div>
    </div>
</template>
