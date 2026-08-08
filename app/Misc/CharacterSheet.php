<?php

namespace App\Misc;

use App\Models\Character;
use App\Models\Skill;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Pure helpers for the printable character sheet.
 *
 * The web sheet is edited a value at a time on a phone; the printed sheet has to
 * show everything at once, so these helpers derive the numbers a player would
 * otherwise have to work out in their head at the table (halves, fifths, the
 * 1920s wealth bands) without storing any of it.
 */
class CharacterSheet
{
    /**
     * The eight core characteristics, in the order the printed sheet lays them out.
     *
     * @return array<int, array{label: string, sub: string|null, value: int}>
     */
    public static function characteristics(Character $character): array
    {
        return [
            ['label' => 'STR', 'sub' => null, 'value' => (int) $character->strength],
            ['label' => 'CON', 'sub' => null, 'value' => (int) $character->constitution],
            ['label' => 'DEX', 'sub' => null, 'value' => (int) $character->dexterity],
            ['label' => 'INT', 'sub' => 'Idea', 'value' => (int) $character->intelligence],
            ['label' => 'SIZ', 'sub' => null, 'value' => (int) $character->size],
            ['label' => 'POW', 'sub' => null, 'value' => (int) $character->power],
            ['label' => 'APP', 'sub' => null, 'value' => (int) $character->appearance],
            ['label' => 'EDU', 'sub' => 'Know', 'value' => (int) $character->education],
        ];
    }

    /**
     * Skills the player has chosen to show, tidied for print and split into
     * three balanced columns.
     *
     * Unlike the screen, this prints the whole list rather than only what has
     * been improved — a paper sheet is where the untouched ones get filled in.
     * The one exception is a skill belonging to another era: it is off the
     * screen too, and printing it would put Fighting (Chainsaw) on a 1925
     * investigator's sheet. Give it a value and it comes back.
     *
     * @return array<int, Collection<int, array{name: string, base: int, value: int, experience: bool}>>
     */
    public static function skillColumns(Character $character, int $columns = 3): array
    {
        $era = $character->era();

        $skills = $character->skills
            ->filter(fn (Skill $skill) => (bool) $skill->pivot->show)
            ->filter(fn (Skill $skill) => $skill->availableIn($era)
                || $skill->pivot->value > $skill->starting_value)
            ->sortBy(fn (Skill $skill) => Str::lower(self::skillName($skill)))
            ->map(fn (Skill $skill) => [
                'name'       => self::skillName($skill),
                'base'       => (int) $skill->starting_value,
                'value'      => (int) $skill->pivot->value,
                'experience' => (bool) $skill->pivot->experience,
            ])
            ->values();

        $perColumn = (int) ceil($skills->count() / $columns);

        return $skills->chunk(max($perColumn, 1))->all();
    }

    /**
     * Skill names are stored in a mix of styles ("Credit_rating", "first_aid"),
     * which is invisible on the phone but glaring in print.
     */
    public static function skillName(Skill $skill): string
    {
        return Str::of($skill->display_name)
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    /**
     * A character's value in a named skill, used to print each weapon's chance to hit.
     */
    public static function skillValue(Character $character, ?string $slug): ?int
    {
        if ($slug === null) {
            return null;
        }

        $skill = $character->skills->firstWhere('slug', $slug);

        return $skill === null ? null : (int) $skill->pivot->value;
    }

    /**
     * Dodge is a skill on the sheet but a derived stat when it was never bought.
     */
    public static function dodge(Character $character): int
    {
        return self::skillValue($character, 'dodge')
            ?: CharacterCreation::dodge($character);
    }

    /**
     * Maximum Sanity is 99 minus the investigator's Cthulhu Mythos.
     */
    public static function maxSanity(Character $character): int
    {
        return 99 - (self::skillValue($character, 'cthulhu_mythos') ?? 0);
    }

    /**
     * The 1920s Credit Rating wealth bands (CoC 7e).
     *
     * @return array{level: string, spending: string, cash: string, assets: string}
     */
    public static function wealth(int $creditRating): array
    {
        return match (true) {
            $creditRating <= 0 => ['level' => 'Penniless', 'spending' => '$0.50', 'cash' => '$0.50', 'assets' => 'None'],
            $creditRating < 10 => ['level' => 'Poor', 'spending' => '$2', 'cash' => self::money($creditRating), 'assets' => self::money($creditRating * 10)],
            $creditRating < 50 => ['level' => 'Average', 'spending' => '$10', 'cash' => self::money($creditRating * 2), 'assets' => self::money($creditRating * 50)],
            $creditRating < 90 => ['level' => 'Wealthy', 'spending' => '$50', 'cash' => self::money($creditRating * 5), 'assets' => self::money($creditRating * 500)],
            $creditRating < 99 => ['level' => 'Rich', 'spending' => '$250', 'cash' => self::money($creditRating * 20), 'assets' => self::money($creditRating * 2000)],
            default            => ['level' => 'Super Rich', 'spending' => '$5,000', 'cash' => '$50,000', 'assets' => '$5M+'],
        };
    }

    private static function money(int|float $amount): string
    {
        return '$'.number_format($amount, $amount < 10 && $amount != (int) $amount ? 2 : 0);
    }
}
