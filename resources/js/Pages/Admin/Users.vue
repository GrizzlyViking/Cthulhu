<script setup>
import { reactive, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { LockClosedIcon, LockOpenIcon, TrashIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    users: { type: Array, required: true },
    roles: { type: Array, required: true },
    group: { type: Object, required: true },
});

// Roles are cumulative, so each row edits a set rather than picking one value.
// The draft is kept locally and only sent when the admin commits it, so an
// intermediate state (say, unticking player before ticking keeper) is never
// saved on its own.
const drafts = reactive(
    Object.fromEntries(props.users.map((user) => [user.id, [...user.roles]]))
);

// Inertia reuses this component across visits, so drafts have to pick up any
// member who was not in the list when it first rendered.
watch(
    () => props.users,
    (users) => {
        users.forEach((user) => {
            if (drafts[user.id] === undefined) {
                drafts[user.id] = [...user.roles];
            }
        });
    }
);

const toggleRole = (user, role) => {
    const draft = drafts[user.id];
    const index = draft.indexOf(role);

    if (index === -1) {
        draft.push(role);
    } else {
        draft.splice(index, 1);
    }
};

const hasRole = (user, role) => drafts[user.id].includes(role);

const isDirty = (user) => {
    const draft = [...drafts[user.id]].sort();
    const saved = [...user.roles].sort();

    return draft.length !== saved.length || draft.some((role, index) => role !== saved[index]);
};

const saveRoles = (user) => {
    router.put(
        route('admin.users.roles.update', { user: user.id }),
        { roles: drafts[user.id] },
        { preserveScroll: true }
    );
};

const resetRoles = (user) => {
    drafts[user.id] = [...user.roles];
};

const toggleBlock = (user) => {
    if (user.blocked) {
        router.delete(route('admin.users.unblock', { user: user.id }), { preserveScroll: true });
        return;
    }

    if (confirm(`Block ${user.name}? They will be signed out and unable to log back in.`)) {
        router.put(route('admin.users.block', { user: user.id }), {}, { preserveScroll: true });
    }
};

const removeUser = (user) => {
    const warning = user.charactersCount > 0
        ? `${user.name} has ${user.charactersCount} investigator(s). Removing them keeps the sheets but takes away their access. Continue?`
        : `Remove ${user.name} from ${props.group.name}?`;

    if (confirm(warning)) {
        router.delete(route('admin.users.destroy', { user: user.id }), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Users" />

    <AdminLayout title="Users" :description="`Everyone in ${group.name}. Other groups are not yours to manage.`">
        <section class="panel p-5 sm:p-6">
            <ul role="list" class="flex flex-col gap-3">
                <li v-for="user in users" :key="user.id" class="card">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="min-w-0 flex-auto">
                            <p class="flex flex-wrap items-center gap-2 text-sm font-semibold text-cthulhu-green-900">
                                {{ user.name }}
                                <span v-if="user.isSelf" class="chip-brass">You</span>
                                <span v-if="user.blocked" class="chip-blood">Blocked</span>
                            </p>
                            <p class="mt-1 flex items-center gap-1.5 text-xs text-cthulhu-green-500">
                                <span
                                    class="size-2 rounded-full"
                                    :class="user.online ? 'bg-cthulhu-green-350' : 'bg-cthulhu-green-500/40'"
                                ></span>
                                {{ user.online ? 'Online' : 'Offline' }} · {{ user.email }} ·
                                <span class="tabular">{{ user.charactersCount }}</span>
                                investigator{{ user.charactersCount === 1 ? '' : 's' }}
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-2">
                            <button
                                type="button"
                                class="btn-secondary btn-sm"
                                :disabled="user.isSelf"
                                @click="toggleBlock(user)"
                            >
                                <component
                                    :is="user.blocked ? LockOpenIcon : LockClosedIcon"
                                    class="size-4"
                                    aria-hidden="true"
                                />
                                {{ user.blocked ? 'Unblock' : 'Block' }}
                            </button>
                            <button
                                type="button"
                                class="btn-danger btn-sm"
                                :disabled="user.isSelf"
                                @click="removeUser(user)"
                            >
                                <TrashIcon class="size-4" aria-hidden="true" />
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 divider pt-4">
                        <p class="eyebrow">Roles</p>

                        <div class="mt-2 flex flex-col gap-2">
                            <label
                                v-for="role in roles"
                                :key="role.value"
                                class="flex cursor-pointer items-start gap-2.5"
                            >
                                <input
                                    type="checkbox"
                                    class="mt-0.5 size-4 shrink-0 rounded border-parchment-400 bg-parchment-50 text-cthulhu-green-800 focus:ring-cthulhu-green-600"
                                    :checked="hasRole(user, role.value)"
                                    @change="toggleRole(user, role.value)"
                                />
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-cthulhu-green-900">{{ role.label }}</span>
                                    <span class="block text-xs text-cthulhu-green-500">{{ role.description }}</span>
                                </span>
                            </label>
                        </div>

                        <div v-if="isDirty(user)" class="mt-3 flex items-center gap-2">
                            <button type="button" class="btn-primary btn-sm" @click="saveRoles(user)">Save roles</button>
                            <button type="button" class="btn-ghost btn-sm" @click="resetRoles(user)">Cancel</button>
                        </div>
                    </div>
                </li>
            </ul>
        </section>
    </AdminLayout>
</template>
