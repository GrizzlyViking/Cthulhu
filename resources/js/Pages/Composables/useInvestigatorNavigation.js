import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/** Keep the campaign shelves identical in the navigation and on the dashboard. */
export function groupInvestigators({ own = [], others = [] } = {}) {
    const inPlay = (character) => character.in_active_game === true;
    const everyone = [...own, ...others];

    return {
        own: own.filter(inPlay),
        others: others.filter(inPlay),
        previous: everyone.filter((character) => !inPlay(character) && character.games?.length),
        unassigned: everyone.filter((character) => !inPlay(character) && !character.games?.length),
    };
}

export const lastGameName = (character) =>
    [...(character.games ?? [])].sort((a, b) => b.id - a.id)[0]?.name ?? null;

export function useInvestigatorNavigation() {
    const page = usePage();
    const characters = computed(() => groupInvestigators(page.props.auth.characters));
    const currentSections = computed(() => [
        { label: 'Your investigators', characters: characters.value.own },
        { label: 'Other investigators', characters: characters.value.others, detail: (character) => character.player?.name },
    ]);
    const previousSections = computed(() => [
        { label: 'Finished campaigns', characters: characters.value.previous, detail: lastGameName },
        { label: 'Not in a game', characters: characters.value.unassigned },
    ].filter((section) => section.characters.length));

    return { characters, currentSections, previousSections };
}
