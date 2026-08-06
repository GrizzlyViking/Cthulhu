<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    BookOpenIcon,
    BriefcaseIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    UserGroupIcon,
    UsersIcon,
    WrenchScrewdriverIcon,
} from '@heroicons/vue/20/solid';

defineProps({
    title: { type: String, required: true },
    description: { type: String, default: '' },
});

const page = usePage();

const sections = [
    { name: 'Overview', route: 'admin.index', pattern: 'admin.index', icon: WrenchScrewdriverIcon },
    { name: 'Group', route: 'admin.group.edit', pattern: 'admin.group.*', icon: UserGroupIcon },
    { name: 'Users', route: 'admin.users.index', pattern: 'admin.users.*', icon: UsersIcon },
    { name: 'Skills', route: 'admin.skills.index', pattern: 'admin.skills.*', icon: BookOpenIcon },
    { name: 'Weapons', route: 'admin.weapons.index', pattern: 'admin.weapons.*', icon: BookOpenIcon },
    { name: 'Equipment', route: 'admin.equipment.index', pattern: 'admin.equipment.*', icon: BriefcaseIcon },
];

const flash = computed(() => page.props.flash ?? {});

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

            <slot />
        </div>
    </AuthenticatedLayout>
</template>
