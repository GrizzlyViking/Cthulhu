<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    group: { type: Object, required: true },
    eras: { type: Array, required: true },
    members: { type: Array, required: true },
    invitations: { type: Array, required: true },
});

const settings = useForm({
    name: props.group.name,
    era: props.group.era,
});

const invite = useForm({
    email: '',
});

const saveSettings = () => settings.put(route('admin.group.update'), { preserveScroll: true });

const sendInvitation = () =>
    invite.post(route('admin.invitations.store'), {
        preserveScroll: true,
        onSuccess: () => invite.reset('email'),
    });

const revokeInvitation = (invitation) => {
    if (confirm(`Revoke the invitation to ${invitation.email}?`)) {
        router.delete(route('admin.invitations.destroy', { invitation: invitation.id }), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Group" />

    <AdminLayout title="Group">
        <!-- Name and era -->
        <section class="panel p-5 sm:p-6">
            <h2 class="display text-lg text-cthulhu-green-900">Settings</h2>

            <form class="mt-4 flex flex-col gap-4" @submit.prevent="saveSettings">
                <div>
                    <label for="group-name" class="field-label">Name</label>
                    <input id="group-name" v-model="settings.name" type="text" class="field mt-1" required />
                    <p v-if="settings.errors.name" class="field-error">{{ settings.errors.name }}</p>
                </div>

                <div>
                    <label for="group-era" class="field-label">Era</label>
                    <select id="group-era" v-model="settings.era" class="field mt-1">
                        <option v-for="era in eras" :key="era.value" :value="era.value">
                            {{ era.label }} ({{ era.value }})
                        </option>
                    </select>
                    <p class="field-hint">Decides which half of the armoury the weapon picker offers.</p>
                    <p v-if="settings.errors.era" class="field-error">{{ settings.errors.era }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary" :disabled="settings.processing">Save</button>
                    <span v-if="settings.isDirty" class="text-xs text-cthulhu-green-500">Unsaved changes</span>
                </div>
            </form>
        </section>

        <!-- Roster -->
        <section class="panel p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="display text-lg text-cthulhu-green-900">Members</h2>
                <Link :href="route('admin.users.index')" class="btn-secondary btn-sm">Manage roles</Link>
            </div>

            <ul role="list" class="mt-4 flex flex-col gap-2">
                <li
                    v-for="member in members"
                    :key="member.id"
                    class="flex flex-wrap items-center justify-between gap-3 card"
                >
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold text-cthulhu-green-900">
                            {{ member.name }}
                            <span v-if="member.blocked" class="chip-blood ml-1">Blocked</span>
                        </p>
                        <p class="text-xs text-cthulhu-green-500">{{ member.email }}</p>
                    </div>

                    <div class="flex shrink-0 flex-wrap items-center gap-1.5">
                        <span v-for="role in member.roles" :key="role" class="chip">{{ role }}</span>
                        <span class="chip-brass tabular">
                            {{ member.charactersCount }} investigator{{ member.charactersCount === 1 ? '' : 's' }}
                        </span>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Invitations -->
        <section class="panel p-5 sm:p-6">
            <h2 class="display text-lg text-cthulhu-green-900">Invitations</h2>
            <p class="field-hint">
                An invitation creates an account in this group when accepted. It expires after seven days.
            </p>

            <form class="mt-4 flex flex-wrap items-start gap-3" @submit.prevent="sendInvitation">
                <div class="min-w-0 flex-auto">
                    <label for="invite-email" class="sr-only">Email address</label>
                    <input
                        id="invite-email"
                        v-model="invite.email"
                        type="email"
                        class="field"
                        placeholder="new.player@example.com"
                        required
                    />
                    <p v-if="invite.errors.email" class="field-error">{{ invite.errors.email }}</p>
                </div>
                <button type="submit" class="btn-primary" :disabled="invite.processing">Send invitation</button>
            </form>

            <ul v-if="invitations.length" role="list" class="mt-4 flex flex-col gap-2">
                <li
                    v-for="invitation in invitations"
                    :key="invitation.id"
                    class="flex flex-wrap items-center justify-between gap-3 card"
                >
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold text-cthulhu-green-900">{{ invitation.email }}</p>
                        <p class="text-xs text-cthulhu-green-500">Expires {{ invitation.expiresAt }}</p>
                    </div>
                    <button type="button" class="btn-danger btn-sm" @click="revokeInvitation(invitation)">
                        <TrashIcon class="size-4" aria-hidden="true" />
                        Revoke
                    </button>
                </li>
            </ul>

            <p v-else class="mt-4 text-sm text-cthulhu-green-500">No invitations are outstanding.</p>
        </section>
    </AdminLayout>
</template>
