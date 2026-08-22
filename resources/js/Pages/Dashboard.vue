<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ContactsList from '@/Pages/Components/ContactsList.vue';
import { useRoles } from '@/Pages/Composables/useRoles.js';

defineProps({
    users: { type: Array, required: true },
    canInvite: { type: Boolean, required: true },
});

const { isKeeper } = useRoles();

const invitation = useForm({
    email: '',
});

const sendInvitation = () =>
    invitation.post(route('invitations.store'), {
        preserveScroll: true,
        onSuccess: () => invitation.reset('email'),
    });
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="display text-2xl text-parchment-100">Dashboard</h1>
            <!-- Rolling in secret lives on the Keeper's screen, next to the party it is rolled against. -->
            <Link v-if="isKeeper" :href="route('keeper.index')" class="btn-secondary btn-sm">Keeper screen</Link>
        </template>

        <div class="page">
            <section class="panel p-4 sm:p-6">
                <contacts-list :users="users"></contacts-list>
            </section>

            <section v-if="canInvite" class="panel p-4 sm:p-6">
                <h2 class="display text-lg text-cthulhu-green-900">Invite another player</h2>
                <p class="field-hint">
                    Anyone at the table may invite another player. Their invitation expires after seven days.
                </p>

                <form class="mt-4 flex flex-wrap items-start gap-3" @submit.prevent="sendInvitation">
                    <div class="min-w-0 flex-auto">
                        <label for="dashboard-invite-email" class="field-label">Email address</label>
                        <input
                            id="dashboard-invite-email"
                            v-model="invitation.email"
                            type="email"
                            class="field mt-1"
                            placeholder="new.player@example.com"
                            required
                        />
                        <p v-if="invitation.errors.email" class="field-error">{{ invitation.errors.email }}</p>
                    </div>
                    <button type="submit" class="btn-primary sm:mt-6" :disabled="invitation.processing">
                        Send invitation
                    </button>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
