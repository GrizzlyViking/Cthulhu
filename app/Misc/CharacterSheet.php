<?php

namespace App\Misc;

use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Skill;
use App\Models\StorageLocation;
use App\Models\Weapon;
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
     * A weapon's damage with the investigator's own damage bonus worked into
     * it: "1D3+DB" becomes "1D3 + 1D4", and "1D3+half DB" becomes
     * "1D3 + half 1D4". The screen resolves it the same way — at a table, "DB"
     * is one more thing to go and look up mid-round.
     *
     * A bonus of "none" takes the clause out altogether rather than printing a
     * plus with nothing after it.
     */
    public static function damage(string $printed, string $damageBonus): string
    {
        $bonus = $damageBonus === 'none' ? null : $damageBonus;

        // Half first: "+half DB" ends in "DB" and would otherwise be caught by
        // the second pattern with the word "half" left stranded in front of it.
        $printed = (string) preg_replace(
            '/\s*\+?\s*half DB$/',
            $bonus === null ? '' : ' + half '.ltrim($bonus, '+'),
            $printed,
        );

        return (string) preg_replace(
            '/\s*\+?\s*DB$/',
            match (true) {
                $bonus === null              => '',
                str_starts_with($bonus, '-') => ' '.$bonus,
                default                      => ' + '.ltrim($bonus, '+'),
            },
            $printed,
        );
    }

    /**
     * Whether the investigator already carries something for fighting with
     * their hands.
     *
     * The printed combat table opens with an unarmed row, because everybody can
     * punch — but the armoury carries Brawl (Unarmed) as a weapon in its own
     * right, and a sheet that has it does not want the line twice.
     */
    public static function carriesUnarmed(Character $character): bool
    {
        return $character->weapons->contains(
            fn (Weapon $weapon): bool => Str::contains(Str::lower($weapon->name), ['unarmed', 'brawl'])
        );
    }

    /**
     * Everything the investigator owns, bucketed by where it is kept.
     *
     * Weapons and equipment are rows in the same pivot, and the printed sheet
     * lists them together for the same reason the screen does: what matters at
     * a table is that the revolver is on the person while the spare box of
     * rounds is in the travel chest. Locations keep their own order, and
     * anything stored nowhere falls in at the end.
     *
     * @return list<array{location: string, items: list<array{name: string, detail: ?string, quantity: int, notes: ?string, weapon: bool}>}>
     */
    public static function possessions(Character $character): array
    {
        /** @var array<int|string, list<array{name: string, detail: ?string, quantity: int, notes: ?string, weapon: bool}>> $owned */
        $owned = [];

        foreach ($character->weapons as $weapon) {
            $owned[self::locationKey($weapon)][] = self::possession($weapon->name, $weapon->category, true, $weapon);
        }

        foreach ($character->equipment as $item) {
            $owned[self::locationKey($item)][] = self::possession($item->name, $item->section, false, $item);
        }

        if ($owned === []) {
            return [];
        }

        $buckets = [];

        foreach (StorageLocation::query()->orderBy('order_by')->orderBy('name')->get() as $location) {
            if (isset($owned[$location->id])) {
                $buckets[] = ['location' => $location->name, 'items' => $owned[$location->id]];

                unset($owned[$location->id]);
            }
        }

        // Whatever is left is kept nowhere in particular, or in a place that has
        // since been retired — either way it is still owned, and still printed.
        $loose = array_merge(...array_values($owned));

        if ($loose !== []) {
            $buckets[] = ['location' => 'Not stored anywhere', 'items' => $loose];
        }

        return $buckets;
    }

    /**
     * The `equipables` row's storage location, or a key for having none.
     */
    private static function locationKey(Weapon|EquipmentItem $thing): int|string
    {
        return $thing->pivot->getAttribute('storage_location_id') ?? 'loose';
    }

    /**
     * @return array{name: string, detail: ?string, quantity: int, notes: ?string, weapon: bool}
     */
    private static function possession(string $name, ?string $detail, bool $isWeapon, Weapon|EquipmentItem $thing): array
    {
        return [
            'name'     => $name,
            'detail'   => $detail,
            'quantity' => (int) ($thing->pivot->getAttribute('quantity') ?? 1),
            'notes'    => $thing->pivot->getAttribute('notes'),
            'weapon'   => $isWeapon,
        ];
    }
}
