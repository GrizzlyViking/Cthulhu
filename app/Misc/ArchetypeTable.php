<?php

namespace App\Misc;

use App\Enums\Archetype;

/**
 * The numbers behind each archetype the Keeper can conjure up.
 *
 * None of this is from the book — the Keeper Rulebook builds NPCs the same way
 * it builds investigators, one point at a time, which is exactly what a Keeper
 * halfway through a scene has no time for. So this is a **house table**, kept in
 * one file rather than spread through the generator, and meant to be tuned:
 * raise a floor, add a weapon, invent an archetype.
 *
 * Every list is a candidate list. The generator picks from it at random and
 * filters weapons and gear by the game's era, so a modern cult gets a Glock
 * where a 1920s one gets a revolver, from the same entry.
 */
class ArchetypeTable
{
    /**
     * Everything one archetype says about the character it makes.
     *
     * - `strong`      characteristics rolled twice, keeping the better roll
     * - `ages`        the band an age is drawn from
     * - `occupations` what the archetype typically is, when the Keeper does not say
     * - `skills`      what it is good at whatever its occupation, and where the
     *                 skill points go first
     * - `combat_floor` the weapon's own skill never lands below this. A cultist
     *                 who cannot hit anything wastes everybody's evening, and
     *                 the Keeper's cast does not have to justify its points
     * - `mythos`      the Cthulhu Mythos band, or null for somebody who has been
     *                 spared. A value here costs sanity, as it should
     * - `weapons`     candidates by name, filtered to the game's era
     * - `gear`        equipment slugs — essentials only, three or four things
     * - `traits`      a hook to play them by, drawn one at a time
     *
     * @return array{
     *     strong: list<string>,
     *     ages: array{int, int},
     *     occupations: list<string>,
     *     skills: list<string>,
     *     combat_floor: int,
     *     mythos: ?array{int, int},
     *     weapons: list<string>,
     *     gear: list<string>,
     *     traits: list<string>,
     * }
     */
    public static function for(Archetype $archetype): array
    {
        return match ($archetype) {
            Archetype::Cultist => [
                'strong'       => ['power'],
                'ages'         => [25, 60],
                'occupations'  => ['Zealot', 'Clergy', 'Missionary', 'Antiquarian', 'Drifter', 'Farmer', 'Artist'],
                'skills'       => ['occult', 'stealth', 'intimidate', 'persuade', 'spot-hidden', 'fighting-brawl'],
                'combat_floor' => 45,
                'mythos'       => [5, 20],
                'weapons'      => [
                    'Knife, Large (machete, etc.)',
                    'Knife, Medium (carving knife, etc.)',
                    'Club, small (nightstick)',
                    'Burning Torch',
                    '.32 or 7.65mm Revolver',
                    '.38 or 9mm Revolver',
                    'Glock 17 9mm Auto',
                ],
                'gear' => [
                    'outdoor-travel-gear--dark-lantern',
                    'outdoor-travel-gear--fifteen-hour-candles-dozen',
                    'tools--rope-50-feet',
                ],
                'traits' => [
                    'Calm to the point of serenity, right up to the knife.',
                    'Talks about the party as though they were expected.',
                    'Flinches at nothing a sane person would flinch at.',
                    'Recites the same half-line of liturgy under their breath.',
                    'Frightened of their own cult, and of what leaving it would mean.',
                ],
            ],

            Archetype::Thug => [
                'strong'       => ['strength', 'constitution'],
                'ages'         => [22, 45],
                'occupations'  => ['Criminal', 'Soldier', 'Athlete', 'Police Officer', 'Drifter'],
                'skills'       => ['fighting-brawl', 'intimidate', 'stealth', 'spot-hidden', 'drive_auto', 'throw'],
                'combat_floor' => 55,
                'mythos'       => null,
                'weapons'      => [
                    'Brass Knuckles',
                    'Blackjack (Cosh, life-preserver)',
                    'Club, large (baseball, cricket bat, poker)',
                    'Knife, Small (switchblade, etc.)',
                    '.38 or 9mm Revolver',
                    '.45 Automatic',
                    '12-gauge Shotgun (2B sawed off)',
                    'Glock 17 9mm Auto',
                ],
                'gear' => [
                    'investigator-tools--cigarettes-per-pack',
                    'outdoor-travel-gear--electric-torch',
                    'investigator-tools--wristwatch',
                ],
                'traits' => [
                    'Paid up front, and counting the minutes.',
                    'Enjoys the work rather more than the wage.',
                    'Will talk instead of fight, if talking is cheaper.',
                    'Nursing an older injury and slow on that side.',
                    'Loyal to whoever fed them last.',
                ],
            ],

            Archetype::Bystander => [
                'strong'      => [],
                'ages'        => [18, 70],
                'occupations' => ['Farmer', 'Drifter', 'Author', 'Librarian', 'Nurse', 'Entertainer', 'Musician', 'Artist', 'Clergy'],
                'skills'      => ['spot-hidden', 'listen', 'psychology', 'first_aid', 'drive_auto'],
                // Left as rolled: an ordinary person is not secretly a brawler.
                'combat_floor' => 0,
                'mythos'       => null,
                'weapons'      => [],
                'gear'         => [
                    'investigator-tools--wristwatch',
                    'outdoor-travel-gear--electric-torch',
                ],
                'traits' => [
                    'Wants to help, and will be in the way while doing it.',
                    'Certain none of this is happening.',
                    'Knows the neighbourhood better than anyone alive.',
                    'Will tell the police everything, at length.',
                    'Already packing to leave town.',
                ],
            ],

            Archetype::Ally => [
                'strong'      => ['education'],
                'ages'        => [30, 65],
                'occupations' => [
                    'Doctor of Medicine', 'Professor', 'Police Detective', 'Private Investigator',
                    'Journalist', 'Librarian', 'Parapsychologist', 'Nurse',
                ],
                'skills'       => ['library_use', 'spot-hidden', 'first_aid', 'psychology', 'persuade', 'occult'],
                'combat_floor' => 30,
                'mythos'       => null,
                'weapons'      => [
                    'Knife, Small (switchblade, etc.)',
                    '.32 or 7.65mm Revolver',
                    '.32 or 7.65mm Automatic',
                    'Glock 17 9mm Auto',
                ],
                'gear' => [
                    'outdoor-travel-gear--electric-torch',
                    'investigator-tools--writing-tablet',
                    'investigator-tools--self-filling-fountain-pen',
                    'medical-equipment--medical-case',
                ],
                'traits' => [
                    'Helpful, and expects to be kept informed.',
                    'Will come along exactly once, then think better of it.',
                    'Sceptical out loud, convinced in private.',
                    'Owes one of the investigators a favour.',
                    'Rather too pleased to be involved.',
                ],
            ],
        };
    }
}
