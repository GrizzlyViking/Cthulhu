<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    group: { type: Object, required: true },
    eras: { type: Array, required: true },
    roles: { type: Array, required: true },
    games: { type: Array, required: true },
    members: { type: Array, required: true },
    invitations: { type: Array, required: true },
});

const settings = useForm({
    name: props.group.name,
    era: props.group.era,
});

const invite = useForm({
    email: '',
    roles: ['player'],
});

/* Games — the group's campaigns. Exactly one is played at a time. */
const newGame = useForm({
    name: '',
    era: props.group.era,
});

const editingGameId = ref(null);

const editGame = useForm({
    name: '',
    era: props.group.era,
});

const createGame = () =>
    newGame.post(route('admin.games.store'), {
        preserveScroll: true,
        onSuccess: () => newGame.reset('name'),
    });

const startEditingGame = (game) => {
    editGame.clearErrors();
    editGame.name = game.name;
    editGame.era = game.era;
    editingGameId.value = game.id;
};

const saveGame = (game) =>
    editGame.put(route('admin.games.update', { game: game.id }), {
        preserveScroll: true,
        onSuccess: () => { editingGameId.value = null; },
    });

const activateGame = (game) =>
    router.put(route('admin.games.activate', { game: game.id }), {}, { preserveScroll: true });

const deleteGame = (game) => {
    if (confirm(`Delete “${game.name}”? Its investigators keep their sheets — they simply leave the game.`)) {
        router.delete(route('admin.games.destroy', { game: game.id }), { preserveScroll: true });
    }
};

const saveSettings = () => settings.put(route('admin.group.update'), { preserveScroll: true });

const sendInvitation = () =>
    invite.post(route('admin.invitations.store'), {
        preserveScroll: true,
        onSuccess: () => invite.reset('email'),
    });

const roleLabel = (value) => props.roles.find((role) => role.value === value)?.label ?? value;

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
                    <p class="field-hint">
                        The era a new game starts in. Each game carries its own from then on — this is only the default.
                    </p>
                    <p v-if="settings.errors.era" class="field-error">{{ settings.errors.era }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary" :disabled="settings.processing">Save</button>
                    <span v-if="settings.isDirty" class="text-xs text-cthulhu-green-500">Unsaved changes</span>
                </div>
            </form>
        </section>

        <!-- Games -->
        <section class="panel p-5 sm:p-6">
            <h2 class="display text-lg text-cthulhu-green-900">Games</h2>
            <p class="field-hint">
                A game is a campaign. The group plays one at a time — everything else is a previous game, and its
                investigators move to the players' “Previous games” menu.
            </p>

            <form class="mt-4 flex flex-wrap items-start gap-3" @submit.prevent="createGame">
                <div class="min-w-0 flex-auto">
                    <label for="game-name" class="sr-only">Name</label>
                    <input
                        id="game-name"
                        v-model="newGame.name"
                        type="text"
                        class="field"
                        placeholder="The Haunting"
                        required
                    />
                    <p v-if="newGame.errors.name" class="field-error">{{ newGame.errors.name }}</p>
                </div>
                <div>
                    <label for="game-era" class="sr-only">Era</label>
                    <select id="game-era" v-model="newGame.era" class="field">
                        <option v-for="era in eras" :key="era.value" :value="era.value">{{ era.label }}</option>
                    </select>
                    <p v-if="newGame.errors.era" class="field-error">{{ newGame.errors.era }}</p>
                </div>
                <button type="submit" class="btn-primary" :disabled="newGame.processing">Create game</button>
            </form>

            <ul v-if="games.length" role="list" class="mt-4 flex flex-col gap-2">
                <li v-for="game in games" :key="game.id" class="card">
                    <!-- Renaming, and moving a campaign to another era. -->
                    <form
                        v-if="editingGameId === game.id"
                        class="flex flex-wrap items-start gap-3"
                        @submit.prevent="saveGame(game)"
                    >
                        <div class="min-w-0 flex-auto">
                            <input v-model="editGame.name" type="text" class="field" required />
                            <p v-if="editGame.errors.name" class="field-error">{{ editGame.errors.name }}</p>
                        </div>
                        <select v-model="editGame.era" class="field w-auto">
                            <option v-for="era in eras" :key="era.value" :value="era.value">{{ era.label }}</option>
                        </select>
                        <button type="submit" class="btn-primary btn-sm" :disabled="editGame.processing">Save</button>
                        <button type="button" class="btn-ghost btn-sm" @click="editingGameId = null">Cancel</button>
                    </form>

                    <div v-else class="flex flex-wrap items-center justify-between gap-3">
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold text-cthulhu-green-900">
                                {{ game.name }}
                                <span v-if="game.active" class="chip-brass ml-1">Playing now</span>
                            </p>
                            <p class="mt-1 text-xs text-cthulhu-green-500">
                                {{ eras.find((era) => era.value === game.era)?.label ?? game.era }}
                                · {{ game.charactersCount }} investigator{{ game.charactersCount === 1 ? '' : 's' }}
                            </p>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-1.5">
                            <button
                                v-if="!game.active"
                                type="button"
                                class="btn-secondary btn-sm"
                                @click="activateGame(game)"
                            >
                                Make active
                            </button>
                            <button type="button" class="btn-ghost btn-sm" @click="startEditingGame(game)">Edit</button>
                            <button
                                type="button"
                                class="btn-danger btn-sm"
                                :disabled="game.active"
                                :title="game.active ? 'Make another game active before deleting this one' : null"
                                @click="deleteGame(game)"
                            >
                                <TrashIcon class="size-4" aria-hidden="true" />
                                Delete
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <p v-else class="mt-4 text-sm text-cthulhu-green-500">
                This group has no games yet. The first one you create becomes the one it plays.
            </p>
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
                An invitation creates an account in this group with the roles you choose. It expires after seven days.
            </p>

            <form class="mt-4 flex flex-col gap-4" @submit.prevent="sendInvitation">
                <div>
                    <label for="invite-email" class="field-label">Email address</label>
                    <input
                        id="invite-email"
                        v-model="invite.email"
                        type="email"
                        class="field mt-1"
                        placeholder="new.player@example.com"
                        required
                    />
                    <p v-if="invite.errors.email" class="field-error">{{ invite.errors.email }}</p>
                </div>

                <fieldset>
                    <legend class="field-label">Roles</legend>
                    <p class="field-hint">Roles are cumulative. Player is selected for a new investigator by default.</p>
                    <div class="mt-2 grid gap-2 sm:grid-cols-3">
                        <label
                            v-for="role in roles"
                            :key="role.value"
                            class="card flex cursor-pointer items-start gap-2.5"
                        >
                            <input
                                v-model="invite.roles"
                                type="checkbox"
                                :value="role.value"
                                class="mt-0.5 size-4 shrink-0 rounded border-parchment-400 bg-parchment-50 text-cthulhu-green-800 focus:ring-cthulhu-green-600"
                            />
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-cthulhu-green-900">{{ role.label }}</span>
                                <span class="block text-xs text-cthulhu-green-500">{{ role.description }}</span>
                            </span>
                        </label>
                    </div>
                    <p v-if="invite.errors.roles" class="field-error">{{ invite.errors.roles }}</p>
                </fieldset>

                <div>
                    <button type="submit" class="btn-primary" :disabled="invite.processing">Send invitation</button>
                </div>
            </form>

            <ul v-if="invitations.length" role="list" class="mt-4 flex flex-col gap-2">
                <li
                    v-for="invitation in invitations"
                    :key="invitation.id"
                    class="flex flex-wrap items-center justify-between gap-3 card"
                >
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold text-cthulhu-green-900">{{ invitation.email }}</p>
                        <p class="mt-1 flex flex-wrap items-center gap-1.5 text-xs text-cthulhu-green-500">
                            <span>Expires {{ invitation.expiresAt }}</span>
                            <span aria-hidden="true">·</span>
                            <span v-for="role in invitation.roles" :key="role" class="chip">{{ roleLabel(role) }}</span>
                        </p>
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
