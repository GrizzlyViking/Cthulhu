<?php

namespace App\Misc;

use App\Enums\Archetype;
use App\Enums\CharacterKind;
use App\Enums\CharacterStatus;
use App\Enums\Era;
use App\Enums\Gender;
use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Game;
use App\Models\Occupation;
use App\Models\Skill;
use App\Models\StorageLocation;
use App\Models\User;
use App\Models\Weapon;
use Illuminate\Support\Collection;

/**
 * A whole non-player character, rolled and equipped in one go.
 *
 * Fire and forget is the point: the Keeper presses Cultist mid-scene and gets
 * somebody playable — characteristics, a skill spread that reads like a person,
 * a weapon with rounds in it, and the three things they would have in their
 * pockets. Nothing is offered to choose beyond the archetype and the occupation,
 * because anything else would be the wizard again.
 *
 * It is deliberately **not** the book's investigator creation:
 *
 * - skill points are spent at random in lumps, so two cultists of the same
 *   occupation differ, rather than being allocated by a player's judgement;
 * - the archetype's combat floor overrides whatever the points said, because a
 *   cultist who cannot hit anybody wastes the evening and the Keeper's cast does
 *   not have to justify itself to anyone;
 * - the ageing rules are skipped — only the age itself is rolled, and the sheet's
 *   move rate already reads it.
 *
 * Everything era-dependent (weapons, gear, names, which occupations exist) comes
 * from the game being played, so the same archetype gives a 1920s cult a revolver
 * and a modern one a Glock.
 */
class NpcGenerator
{
    /**
     * The ceiling any one skill lands under. The Investigator Handbook caps a
     * 1920s investigator's skills at 75 at creation; the Keeper's cast keeps to
     * the same ceiling so its numbers stay readable against the party's.
     */
    private const int SKILL_CEILING = 75;

    /**
     * @param array{
     *     strong: list<string>,
     *     ages: array{int, int},
     *     occupations: list<string>,
     *     skills: list<string>,
     *     combat_floor: int,
     *     mythos: ?array{int, int},
     *     weapons: list<string>,
     *     gear: list<string>,
     *     traits: list<string>,
     * } $traits
     */
    private function __construct(
        private readonly Archetype $archetype,
        private readonly Game $game,
        private readonly User $keeper,
        private readonly array $traits,
    ) {}

    /**
     * Conjure one up. The occupation is the Keeper's choice; without one, the
     * archetype picks something typical of itself.
     */
    public static function conjure(Archetype $archetype, Game $game, User $keeper, ?Occupation $occupation = null): Character
    {
        $generator = new self($archetype, $game, $keeper, ArchetypeTable::for($archetype));

        return $generator->generate($occupation);
    }

    private function generate(?Occupation $occupation): Character
    {
        $era = $this->game->era;
        $occupation ??= $this->typicalOccupation($era);
        $gender = random_int(0, 1) === 0 ? Gender::Male : Gender::Female;
        $name   = NpcNames::random($era, $gender);

        $character = Character::create([
            'name'          => $name,
            'slug'          => Character::uniqueSlug($name),
            'user_id'       => null,
            'keeper_id'     => $this->keeper->id,
            'group_id'      => $this->game->group_id,
            'kind'          => CharacterKind::NonPlayer,
            'archetype'     => $this->archetype,
            'status'        => CharacterStatus::Complete,
            'gender'        => $gender->name,
            'age'           => random_int($this->traits['ages'][0], $this->traits['ages'][1]),
            'occupation'    => $occupation?->name,
            'occupation_id' => $occupation?->id,
            'backstory'     => [
                'personal_description' => $this->archetype->description(),
                'traits'               => $this->pick($this->traits['traits']),
            ],
            ...$this->characteristics(),
        ]);

        $this->derive($character);
        $character->addAllSkills();

        // The weapon is chosen before the skills so the archetype's combat floor
        // lands on the skill they will actually be rolling.
        $weapon = $this->chooseWeapon($era);

        $this->spendSkillPoints($character, $occupation, $weapon, $era);
        $this->equip($character, $weapon, $era);

        $character->games()->attach($this->game->id);

        return $character->refresh();
    }

    /**
     * The eight characteristics. The archetype's strong suit is rolled twice and
     * the better roll kept — which is how a thug ends up stronger than average
     * without anybody choosing a number.
     *
     * @return array<string, int>
     */
    private function characteristics(): array
    {
        return [
            'strength'     => $this->characteristic('strength', '(3d6)*5'),
            'constitution' => $this->characteristic('constitution', '(3d6)*5'),
            'dexterity'    => $this->characteristic('dexterity', '(3d6)*5'),
            'appearance'   => $this->characteristic('appearance', '(3d6)*5'),
            'power'        => $this->characteristic('power', '(3d6)*5'),
            'size'         => $this->characteristic('size', '(2d6+6)*5'),
            'intelligence' => $this->characteristic('intelligence', '(2d6+6)*5'),
            'education'    => $this->characteristic('education', '(2d6+6)*5'),
            'luck'         => Roll::dice('(3d6)*5'),
        ];
    }

    private function characteristic(string $name, string $dice): int
    {
        $rolled = Roll::dice($dice);

        return in_array($name, $this->traits['strong'], true)
            ? max($rolled, Roll::dice($dice))
            : $rolled;
    }

    /**
     * The stats that follow from the characteristics. Sanity is POW, as ever —
     * until the Mythos takes its cut in {@see self::spendSkillPoints()}.
     */
    private function derive(Character $character): void
    {
        $character->hit_points   = CharacterCreation::hitPoints($character);
        $character->sanity       = CharacterCreation::sanity($character);
        $character->magic_points = CharacterCreation::magicPoints($character);
        $character->dodge        = CharacterCreation::dodge($character);
        $character->build        = CharacterCreation::build($character);
        $character->damage_bonus = CharacterCreation::damageBonus($character);
        $character->move_rate    = CharacterCreation::moveRate($character);
        $character->save();
    }

    /**
     * Something this archetype typically does for a living, of the ones this era
     * has. Falls back to any occupation of the era, and to none at all on a
     * server whose occupations have never been seeded — the archetype's own
     * skills still make a usable character.
     */
    private function typicalOccupation(Era $era): ?Occupation
    {
        $available = Occupation::all()
            ->filter(fn (Occupation $occupation): bool => in_array($era->value, $occupation->eras, true));

        $typical = $available->whereIn('name', $this->traits['occupations']);

        $chosen = $typical->isNotEmpty() ? $typical->random() : $available->shuffle()->first();

        return $chosen instanceof Occupation ? $chosen : null;
    }

    /**
     * Everything the character can roll, and what they roll it against.
     *
     * The occupation's own list is honoured — its choices ("one interpersonal
     * skill", "any two others") are drawn at random, favouring what the archetype
     * is good at. Credit Rating is rolled inside the occupation's band and paid
     * for out of the same pool, as the book has it.
     */
    private function spendSkillPoints(Character $character, ?Occupation $occupation, ?Weapon $weapon, Era $era): void
    {
        /** @var Collection<string, Skill> $skills */
        $skills = Skill::query()->inEra($era)->get()->keyBy('slug');

        /** @var array<string, int> $values */
        $values = $skills->map(fn (Skill $skill): int => (int) $skill->starting_value)->all();

        // The two the book bases on the character rather than on the skill.
        if (array_key_exists('language_own', $values)) {
            $values['language_own'] = (int) $character->education;
        }
        if (array_key_exists('dodge', $values)) {
            $values['dodge'] = CharacterCreation::dodge($character);
        }

        $favourites = array_values(array_filter(
            $this->traits['skills'],
            fn (string $slug): bool => array_key_exists($slug, $values),
        ));

        $creditRating = $this->creditRating($occupation);
        $pool         = $occupation === null ? 0 : $occupation->skillPointsFor($character);

        if (array_key_exists('credit_rating', $values)) {
            $creditRating            = min($creditRating, max($pool, 0));
            $values['credit_rating'] = max($values['credit_rating'], $creditRating);
            $pool -= $creditRating;
        }

        // The occupation's points go on the occupation's skills, weighted toward
        // what the archetype is good at by listing those twice.
        $occupationSkills = $occupation === null
            ? $favourites
            : $this->resolveOccupationSkills($occupation, $values, $favourites);

        $this->spend(max($pool, 0), [...$occupationSkills, ...$favourites], $values);

        // Personal interest points, as the book gives them: INT × 2, on whatever
        // the archetype is about plus a couple of things nobody would guess.
        $this->spend(
            (int) $character->intelligence * 2,
            [...$favourites, ...$this->sample(array_keys($values), 3)],
            $values,
        );

        $this->applyCombatFloor($values, $weapon);
        $this->applyMythos($character, $values);

        $this->writeSkills($character, $skills, $values);
    }

    /**
     * The occupation's skill list with its choices settled: a plain slug stands,
     * "one interpersonal skill" draws from its options, and "any other skill"
     * draws from what the archetype is good at before anything else this era has.
     *
     * @param  array<string, int> $values
     * @param  list<string>       $favourites
     * @return list<string>
     */
    private function resolveOccupationSkills(Occupation $occupation, array $values, array $favourites): array
    {
        $resolved = [];

        foreach ($occupation->skills as $entry) {
            if (is_string($entry)) {
                $resolved[] = $entry;

                continue;
            }

            $count   = (int) ($entry['count'] ?? 1);
            $options = $entry['type'] === 'any'
                ? [...$favourites, ...array_keys($values)]
                : ($entry['options'] ?? array_keys($values));

            $resolved = [...$resolved, ...$this->sample($options, $count)];
        }

        return array_values(array_filter(
            $resolved,
            fn (string $slug): bool => array_key_exists($slug, $values) && $slug !== 'credit_rating',
        ));
    }

    /**
     * How well off they are: somewhere in the occupation's printed band. Without
     * an occupation, the poor end of the scale — a hired knife is not wealthy.
     */
    private function creditRating(?Occupation $occupation): int
    {
        if ($occupation === null) {
            return random_int(5, 25);
        }

        $min = min($occupation->credit_rating_min, $occupation->credit_rating_max);
        $max = max($occupation->credit_rating_min, $occupation->credit_rating_max);

        return random_int($min, $max);
    }

    /**
     * Spread a pool of points across candidates in uneven lumps and in no
     * particular order, so two characters built from the same archetype and
     * occupation still come out different people. A slug listed more than once is
     * simply likelier to be picked, which is how the weighting works.
     *
     * @param list<string>       $candidates
     * @param array<string, int> $values
     */
    private function spend(int $pool, array $candidates, array &$values): void
    {
        $candidates = array_values(array_filter(
            $candidates,
            fn (string $slug): bool => array_key_exists($slug, $values),
        ));

        while ($pool > 0 && $candidates !== []) {
            $open = array_values(array_filter(
                $candidates,
                fn (string $slug): bool => $values[$slug] < self::SKILL_CEILING,
            ));

            if ($open === []) {
                return;
            }

            $slug = $open[random_int(0, count($open) - 1)];
            $lump = min($pool, random_int(5, 20), self::SKILL_CEILING - $values[$slug]);

            $values[$slug] += $lump;
            $pool -= $lump;
        }
    }

    /**
     * The archetype's floor under whatever it fights with, and under its own
     * fighting skills. It overrides the points spent above on purpose — see the
     * class comment.
     *
     * @param array<string, int> $values
     */
    private function applyCombatFloor(array &$values, ?Weapon $weapon): void
    {
        $floor = $this->traits['combat_floor'];

        if ($floor <= 0) {
            return;
        }

        $slugs = array_filter(
            $this->traits['skills'],
            fn (string $slug): bool => str_starts_with($slug, 'fighting') || str_starts_with($slug, 'firearms'),
        );

        if ($weapon?->skill !== null) {
            $slugs[] = $weapon->skill;
        }

        foreach ($slugs as $slug) {
            if (array_key_exists($slug, $values)) {
                $values[$slug] = max($values[$slug], $floor);
            }
        }
    }

    /**
     * What they know that they should not. Knowing it costs sanity: the book caps
     * maximum sanity at 99 minus Cthulhu Mythos, and a cultist has usually spent
     * a good deal more than that already.
     *
     * @param array<string, int> $values
     */
    private function applyMythos(Character $character, array &$values): void
    {
        $band = $this->traits['mythos'];

        if ($band === null || ! array_key_exists('cthulhu_mythos', $values)) {
            return;
        }

        $mythos                   = random_int($band[0], $band[1]);
        $values['cthulhu_mythos'] = max($values['cthulhu_mythos'], $mythos);

        $character->sanity = min((int) $character->sanity, 99 - $mythos);
        $character->save();
    }

    /**
     * Write the figures onto the sheet, touching only the skills that moved.
     *
     * @param Collection<string, Skill> $skills
     * @param array<string, int>        $values
     */
    private function writeSkills(Character $character, Collection $skills, array $values): void
    {
        foreach ($values as $slug => $value) {
            $skill = $skills->get($slug);

            if ($skill === null || $value === (int) $skill->starting_value) {
                continue;
            }

            $character->skills()->updateExistingPivot($skill->id, ['value' => min($value, 99)]);
        }
    }

    /**
     * What they are holding, from the archetype's candidates that this era has.
     * A bystander holds nothing, and neither does anybody whose candidates all
     * belong to another era — better empty-handed than anachronistic.
     */
    private function chooseWeapon(Era $era): ?Weapon
    {
        if ($this->traits['weapons'] === []) {
            return null;
        }

        return Weapon::query()
            ->whereIn('name', $this->traits['weapons'])
            ->inEra($era)
            ->get()
            ->shuffle()
            ->first();
    }

    /**
     * The weapon and the essentials. A firearm arrives loaded with one more
     * magazine in a pocket — the Keeper's screen reads those rounds, and an NPC
     * who runs dry after six shots is a scene, not a bug.
     */
    private function equip(Character $character, ?Weapon $weapon, Era $era): void
    {
        $location = StorageLocation::query()->orderBy('order_by')->value('id');

        if ($weapon !== null) {
            $capacity = $weapon->magazine_capacity;

            $character->weapons()->attach($weapon->id, [
                'ammo'                => $capacity ?? 0,
                'ammo_reserve'        => $capacity ?? 0,
                'storage_location_id' => $location,
                'quantity'            => 1,
            ]);
        }

        EquipmentItem::query()
            ->whereIn('slug', $this->traits['gear'])
            ->inEra($era)
            ->get()
            ->each(fn (EquipmentItem $item) => $character->equipment()->attach($item->id, [
                'storage_location_id' => $location,
                'quantity'            => 1,
            ]));
    }

    /**
     * Distinct random picks, or as many as there are.
     *
     * @param  list<string>|array<int, string> $values
     * @return list<string>
     */
    private function sample(array $values, int $count): array
    {
        $unique = array_values(array_unique($values));

        if ($unique === [] || $count < 1) {
            return [];
        }

        shuffle($unique);

        return array_slice($unique, 0, min($count, count($unique)));
    }

    /**
     * @param list<string> $values
     */
    private function pick(array $values): string
    {
        return $values === [] ? '' : $values[random_int(0, count($values) - 1)];
    }
}
