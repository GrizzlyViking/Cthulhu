<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { BookOpenIcon, BriefcaseIcon, UserGroupIcon, UsersIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    group: { type: Object, default: null },
    counts: { type: Object, required: true },
    referenceDataEditable: { type: Boolean, required: true },
});

const cards = [
    {
        name: 'Group',
        route: 'admin.group.edit',
        icon: UserGroupIcon,
        summary: () => `${props.counts.members} member${props.counts.members === 1 ? '' : 's'}, ` +
            `${props.counts.invitations} invitation${props.counts.invitations === 1 ? '' : 's'} pending`,
        description: () => 'Rename the group, set its era, and invite new investigators.',
    },
    {
        name: 'Users',
        route: 'admin.users.index',
        icon: UsersIcon,
        summary: () => `${props.counts.characters} investigator${props.counts.characters === 1 ? '' : 's'} between them`,
        description: () => 'Grant roles, block a member, or remove them from the table.',
    },
    {
        name: 'Skills',
        route: 'admin.skills.index',
        icon: BookOpenIcon,
        summary: () => `${props.counts.skills} skills`,
        description: () => props.referenceDataEditable
            ? 'Add, edit and retire the skills every character draws from.'
            : 'Browse the canonical skill list every character draws from.',
    },
    {
        name: 'Weapons',
        route: 'admin.weapons.index',
        icon: BookOpenIcon,
        summary: () => `${props.counts.weapons} weapons`,
        description: () => props.referenceDataEditable
            ? 'Add, edit and retire weapons in the shared armoury.'
            : 'Browse the armoury as the Investigator Handbook prints it.',
    },
    {
        name: 'Equipment',
        route: 'admin.equipment.index',
        icon: BriefcaseIcon,
        summary: () => `${props.counts.equipment} items`,
        description: () => props.referenceDataEditable
            ? 'The 1920s catalogue, the places things are kept, and whatever players have added.'
            : 'Browse the 1920s equipment catalogue and the places things are kept.',
    },
];
</script>

<template>
    <Head title="Administration" />

    <AdminLayout title="Administration">
        <!-- An admin with no group has nothing to administer; say so plainly
             rather than showing four pages of zeroes. -->
        <section v-if="group === null" class="panel p-6">
            <h2 class="display text-lg text-cthulhu-green-900">You are not in a group yet</h2>
            <p class="mt-2 max-w-prose text-sm text-cthulhu-green-700">
                Admin authority is per-group: you manage the group you belong to and no other. Until
                someone puts you in one, there is nothing here to manage.
            </p>
            <p class="mt-4 max-w-prose text-sm text-cthulhu-green-700">
                Whoever runs the server can create a group and place you in it from the console:
            </p>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-cthulhu-green-900 p-4 text-xs text-parchment-100"><code>php artisan group:create
php artisan player:assign {{ $page.props.auth.user.email }}</code></pre>
        </section>

        <template v-else>
            <section class="panel p-6">
                <p class="eyebrow">Managing</p>
                <h2 class="display mt-1 text-2xl text-cthulhu-green-900">{{ group.name }}</h2>
                <p v-if="group.era" class="mt-1 text-sm text-cthulhu-green-700">Era: {{ group.era }}</p>
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <Link
                    v-for="card in cards"
                    :key="card.name"
                    :href="route(card.route)"
                    class="panel block p-5 transition hover:ring-2 hover:ring-cthulhu-yellow-500"
                >
                    <div class="flex items-center gap-2">
                        <component :is="card.icon" class="size-5 text-cthulhu-green-700" aria-hidden="true" />
                        <h3 class="display text-lg text-cthulhu-green-900">{{ card.name }}</h3>
                    </div>
                    <p class="mt-2 text-sm text-cthulhu-green-700">{{ card.description() }}</p>
                    <p class="mt-3 chip tabular">{{ card.summary() }}</p>
                </Link>
            </section>

            <section class="panel p-5">
                <p class="eyebrow">Beyond this group</p>
                <p class="mt-2 max-w-prose text-sm text-cthulhu-green-700">
                    Creating groups and moving a player between them reach across every table on this
                    server, so they stay with the console commands rather than living here. Run
                    <code class="rounded bg-cthulhu-green-900/10 px-1 py-0.5 text-xs">php artisan cthulhu:manage</code>
                    for the interactive menu.
                </p>
                <p v-if="referenceDataEditable" class="mt-3 max-w-prose text-sm text-cthulhu-green-700">
                    Skills and weapons are shared the same way, but editing them from here is switched on
                    while one group plays on this server. Turn
                    <code class="rounded bg-cthulhu-green-900/10 px-1 py-0.5 text-xs">CTHULHU_ADMIN_EDIT_REFERENCE_DATA</code>
                    off once a second group exists, and both lists go back to being console-only.
                </p>
            </section>
        </template>
    </AdminLayout>
</template>
