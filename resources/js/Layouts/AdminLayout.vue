<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    BookOpenIcon,
    BriefcaseIcon,
    IdentificationIcon,
    UserGroupIcon,
    UsersIcon,
    WrenchScrewdriverIcon,
} from '@heroicons/vue/20/solid';

defineProps({
    title: { type: String, required: true },
    description: { type: String, default: '' },
});

const sections = [
    { name: 'Overview', route: 'admin.index', pattern: 'admin.index', icon: WrenchScrewdriverIcon },
    { name: 'Group', route: 'admin.group.edit', pattern: 'admin.group.*', icon: UserGroupIcon },
    { name: 'Users', route: 'admin.users.index', pattern: 'admin.users.*', icon: UsersIcon },
    { name: 'Skills', route: 'admin.skills.index', pattern: 'admin.skills.*', icon: BookOpenIcon },
    { name: 'Occupations', route: 'admin.occupations.index', pattern: 'admin.occupations.*', icon: IdentificationIcon },
    { name: 'Weapons', route: 'admin.weapons.index', pattern: 'admin.weapons.*', icon: BookOpenIcon },
    { name: 'Equipment', route: 'admin.equipment.index', pattern: 'admin.equipment.*', icon: BriefcaseIcon },
];

const linkClass = (pattern) => [
    'inline-flex items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-semibold transition',
    route().current(pattern)
        ? 'bg-cthulhu-green-800 text-cthulhu-yellow-400'
        : 'text-cthulhu-green-700 hover:bg-cthulhu-green-900/10 hover:text-cthulhu-green-900',
];
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div>
                <p class="eyebrow-on-dark">Administration</p>
                <h1 class="display text-2xl text-parchment-100">{{ title }}</h1>
            </div>
            <slot name="actions" />
        </template>

        <div class="page">
            <!-- Section navigation -->
            <nav class="panel overflow-x-auto p-2">
                <div class="flex items-center gap-1">
                    <Link
                        v-for="section in sections"
                        :key="section.name"
                        :href="route(section.route)"
                        :class="linkClass(section.pattern)"
                    >
                        <component :is="section.icon" class="size-4 opacity-70" aria-hidden="true" />
                        {{ section.name }}
                    </Link>
                </div>
            </nav>

            <p v-if="description" class="text-sm text-cthulhu-green-200">{{ description }}</p>

            <!-- The result of the last action comes from AuthenticatedLayout. -->
            <slot />
        </div>
    </AuthenticatedLayout>
</template>
