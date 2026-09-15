import { expect, it } from 'vitest';
import { groupInvestigators, lastGameName } from '@/Pages/Composables/useInvestigatorNavigation.js';

it('keeps active, previous and unassigned sheets distinct without losing deleted investigators', () => {
    const own = [
        { slug: 'current', in_active_game: true, games: [{ id: 2 }] },
        { slug: 'kept', in_active_game: true, is_deleted: true, games: [{ id: 2 }] },
        { slug: 'previous', in_active_game: false, games: [{ id: 1 }] },
        { slug: 'unassigned', games: [] },
    ];
    const others = [{ slug: 'party', in_active_game: true, games: [{ id: 2 }] }];
    const groups = groupInvestigators({ own, others });
    expect(groups.own.map(c => c.slug)).toEqual(['current', 'kept']);
    expect(groups.others.map(c => c.slug)).toEqual(['party']);
    expect(groups.previous.map(c => c.slug)).toEqual(['previous']);
    expect(groups.unassigned.map(c => c.slug)).toEqual(['unassigned']);
    expect(Object.values(groups).flat()).toHaveLength(5);
    expect(own).toHaveLength(4);
});

it('labels previous games by the latest game without sorting the original list', () => {
    const games = [{ id: 1, name: 'First' }, { id: 3, name: 'Latest' }, { id: 2, name: 'Second' }];
    expect(lastGameName({ games })).toBe('Latest');
    expect(games.map(game => game.id)).toEqual([1, 3, 2]);
    expect(lastGameName({})).toBeNull();
    expect(groupInvestigators()).toEqual({ own: [], others: [], previous: [], unassigned: [] });
});
