<script setup>
import { computed } from 'vue';
import InvestigatorCard from '@/Components/InvestigatorCard.vue';
import { useInvestigatorNavigation } from '@/Pages/Composables/useInvestigatorNavigation.js';
import { PlusIcon } from '@heroicons/vue/20/solid';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ContactsList from '@/Pages/Components/ContactsList.vue';
import { useRoles } from '@/Pages/Composables/useRoles.js';

defineProps({
    users: { type: Array, required: true },
    canInvite: { type: Boolean, required: true },
});

const { isKeeper } = useRoles();
const { characters } = useInvestigatorNavigation();
const investigators = computed(() => characters.value.own);

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
                <div class="flex flex-wrap items-start justify-between gap-4 border-b border-parchment-300 pb-5">
                    <div>
                        <p class="eyebrow">Current campaign</p>
                        <h2 class="display mt-1 text-2xl text-cthulhu-green-900">Your investigators</h2>
                        <p class="field-hint">Pick up a sheet and return to the story.</p>
                    </div>
                    <Link :href="route('character.create')" class="btn-primary">
                        <PlusIcon class="size-4" aria-hidden="true" />
                        Create investigator
                    </Link>
                </div>
                <div v-if="investigators.length" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <InvestigatorCard v-for="character in investigators" :key="character.slug" :character="character" />
                </div>
                <div v-else class="py-6">
                    <h3 class="display text-lg text-cthulhu-green-900">Your next story starts here</h3>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-cthulhu-green-500">
                        You have no investigator in the current campaign. Create one to join the party, or find an existing sheet under Previous games in the navigation.
                    </p>
                </div>
            </section>

            <section class="panel p-4 sm:p-6">
                <div class="mb-4 flex items-baseline justify-between gap-3">
                    <h2 class="text-base font-semibold text-cthulhu-green-900">At the table</h2>
                    <span class="eyebrow tabular">{{ users.length }} {{ users.length === 1 ? 'player' : 'players' }}</span>
                </div>
                <ContactsList :users="users" />
                <p v-if="!users.length" class="field-hint">No players have joined yet. An invitation brings them to the table.</p>
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
                        {{ invitation.processing ? 'Sending…' : 'Send invitation' }}
                    </button>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
