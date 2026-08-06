import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * Roles are cumulative — a user may be a player, a keeper and an admin at the
 * same time — so everything here asks "does this user wear that hat", never
 * "is that the user's one role".
 */
export function useRoles() {
    const page = usePage();

    const roles = computed(() => page.props.auth?.roles ?? []);

    const hasRole = (role) => roles.value.includes(role);

    return {
        roles,
        hasRole,
        isPlayer: computed(() => hasRole('player')),
        isKeeper: computed(() => hasRole('keeper')),
        isAdmin: computed(() => hasRole('admin')),
    };
}
