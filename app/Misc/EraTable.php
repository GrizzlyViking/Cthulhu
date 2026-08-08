<?php

namespace App\Misc;

use App\Enums\Era;

/**
 * Which eras a skill, a weapon or a piece of equipment belongs to.
 *
 * Only the weapons come from the book: the Investigator Handbook prints an
 * availability cell for every weapon, and {@see forWeapon()} reads it. Nothing
 * else on this server has an era in print, so the skills and the equipment
 * catalogue are **guesses** — reasonable ones, but a Keeper who disagrees can
 * change any of them on the admin pages. They are collected here rather than
 * scattered through the seeders so that the guessing is in one reviewable file.
 *
 * The result is always a non-empty list of {@see Era} values. Something
 * available throughout carries every era, which is also what anything new
 * defaults to.
 */
class EraTable
{
    /**
     * Skills that belong to one era only.
     *
     * Almost nothing does. The 7th edition rulebook's era-specific skills are
     * Computer Use and Electronics, and this server's list has neither; what is
     * left is the handful whose *equipment* did not exist yet — the chainsaw
     * the handbook itself prints as Modern.
     *
     * @var array<string, list<string>>
     */
    public const SKILLS = [
        'fighting-chainsaw' => [Era::Modern->value],
    ];

    /**
     * Sections of the equipment catalogue that are of their time as a whole.
     *
     * The catalogue is the handbook's 1920s shopping lists, so most of it is
     * ordinary gear that a modern investigator would still recognise — rope,
     * a compass, a first aid case. The wardrobes are the exception: a
     * Chesterfield overcoat, sock garters and a rayon elastic corset date a
     * scene the moment they are worn.
     *
     * @var array<string, list<string>>
     */
    public const EQUIPMENT_SECTIONS = [
        EquipmentTable::MENS_CLOTHING   => [Era::Twenties->value],
        EquipmentTable::WOMENS_CLOTHING => [Era::Twenties->value],
        EquipmentTable::PERSONAL_CARE   => [Era::Twenties->value],
    ];

    /**
     * Items inside a period section that have not really changed since — a
     * bathing suit is a bathing suit.
     *
     * @var list<string>
     */
    public const EQUIPMENT_TIMELESS = [
        'men-s-clothing--oxford-dress-shoes',
        'men-s-clothing--leather-work-shoes',
        'men-s-clothing--straw-hat',
        'men-s-clothing--sweatshirt',
        'men-s-clothing--necktie-silk',
        'men-s-clothing--cufflinks',
        'men-s-clothing--leather-belt',
        'men-s-clothing--suspenders',
        'men-s-clothing--hiking-boots',
        'men-s-clothing--shoes-with-cleats',
        'men-s-clothing--bathing-suit',
        'women-s-clothing--blouse-cotton',
        'women-s-clothing--worsted-wool-sweater',
        'women-s-clothing--tweed-jacket-fully-lined',
        'women-s-clothing--silk-handbag',
        'women-s-clothing--dress-hair-comb',
        'women-s-clothing--outdoor-boots',
        'women-s-clothing--shoes-pumps',
        'women-s-clothing--bathing-suit',
        'women-s-clothing--bathing-cap',
        'personal-care--make-up-kit',
        'personal-care--hair-brush',
        'personal-care--mouthwash-listerine',
        'personal-care--shampoo-coconut-oil',
        'personal-care--soap-12-cakes',
        'personal-care--talcum-powder',
        'personal-care--toothpaste-pepsodent',
    ];

    /**
     * Items outside the period sections that a modern investigator could not
     * walk into a shop and buy: obsolete lighting, obsolete recording, obsolete
     * cartridges, and the luggage you only need when the crossing takes a week.
     *
     * @var list<string>
     */
    public const EQUIPMENT_PERIOD_ONLY = [
        'medical-equipment--atomizer',
        'medical-equipment--hard-rubber-syringe',
        'medical-equipment--maple-crutches',
        'outdoor-travel-gear--carbide-lamp-300-beam',
        'outdoor-travel-gear--can-of-carbide-two-pounds',
        'outdoor-travel-gear--gasoline-lantern-built-in-pump',
        'outdoor-travel-gear--dark-lantern',
        'outdoor-travel-gear--folding-bathtub',
        'outdoor-travel-gear--field-glasses-3x-to-6x',
        'luggage--steamer-trunk-55-lbs',
        'luggage--wardrobe-trunk-95-lbs',
        'luggage--wardrobe-trunk-115-lbs',
        'tents-camp--auto-bed',
        'investigator-tools--dictaphone',
        'investigator-tools--wire-recorder',
        'investigator-tools--remington-typewriter',
        'investigator-tools--harris-typewriter',
        'investigator-tools--wet-sponge-respirator',
        'investigator-tools--complete-diving-suit',
        'investigator-tools--folding-writing-desk',
        'communications--telegraph-outfit',
        'entertainment--phonograph-records',
        'entertainment--box-brownie-camera',
        'entertainment--film-24-exposures',
        'entertainment--film-developing-kit',
        'entertainment--kodak-folding-no-1-camera',
        'entertainment--eastman-commercial-camera',
        'entertainment--16mm-movie-camera-projector',
        'sports-games--150-clay-marbles',
        'sports-games--bamboo-vaulting-pole-12-foot',
        'ammunition--25-rimfire-box-of-100',
        'ammunition--32-20-repeater-box-of-100',
        'ammunition--38-short-round-box-of-100',
        'ammunition--38-55-repeater-box-of-100',
        'ammunition--44-hi-power-box-of-100',
        'ammunition--10-gauge-shell-box-of-25',
        'ammunition--10-gauge-shell-box-of-100',
    ];

    /**
     * Weapons whose printed availability cell contradicts the rest of their own
     * row, keyed by the name {@see WeaponTable} gives them.
     *
     * The handbook prints Mace Spray as "1920s, Modern" but prices it "-/$10" —
     * no 1920s price at all — and chemical mace was not invented until the
     * 1960s. The cost cell and the calendar agree against the era cell, so this
     * follows them.
     *
     * @var array<string, list<string>>
     */
    public const WEAPONS = [
        'Mace Spray' => [Era::Modern->value],
    ];

    /**
     * @return list<string>
     */
    public static function forSkill(string $slug): array
    {
        return self::SKILLS[$slug] ?? Era::all();
    }

    /**
     * Read the handbook's availability cell.
     *
     * The column is headed "Common in Era" and the Key on p.255 glosses it as
     * "availability by era", with "Rare" defined separately as "perhaps
     * obsolete; a fine specimen for collectors or perhaps illegal". So "Rare"
     * is a note about how hard a thing is to come by, not about when: it only
     * narrows the eras when it sits beside one. The four weapons marked "Rare"
     * alone — the flintlock, the thrown spear, the war boomerang and the .58
     * Springfield — each carry a price on both sides of the book's "Cost 20s/
     * Modern" cell, which is the book agreeing that they can be had in either.
     * Contrast the .41 Revolver, "1920s, Rare" at "$30/-": a dash where the
     * modern price would be, and 1920s only.
     *
     * Anything unrecognised — a house ruled weapon with the cell left empty —
     * is available throughout.
     *
     * @return list<string>
     */
    public static function forWeapon(?string $printed, ?string $name = null): array
    {
        if ($name !== null && isset(self::WEAPONS[$name])) {
            return self::WEAPONS[$name];
        }

        $cell = mb_strtolower((string) $printed);

        $eras = [];

        if (str_contains($cell, '1920')) {
            $eras[] = Era::Twenties->value;
        }

        // WWII kit is past the 1920s and still in service, so it reads as modern.
        if (str_contains($cell, 'modern') || str_contains($cell, 'wwii') || str_contains($cell, 'later')) {
            $eras[] = Era::Modern->value;
        }

        return $eras === [] ? Era::all() : array_values(array_unique($eras));
    }

    /**
     * @return list<string>
     */
    public static function forEquipment(string $slug, ?string $section): array
    {
        if (in_array($slug, self::EQUIPMENT_PERIOD_ONLY, true)) {
            return [Era::Twenties->value];
        }

        if (in_array($slug, self::EQUIPMENT_TIMELESS, true)) {
            return Era::all();
        }

        return self::EQUIPMENT_SECTIONS[$section] ?? Era::all();
    }

    /**
     * Keep only the values that are eras, in the enum's own order, and fall
     * back to every era rather than to nothing — an empty list would mean a
     * thing no group could ever see.
     *
     * @param  mixed        $eras
     * @return list<string>
     */
    public static function normalise($eras): array
    {
        $values = array_values(array_intersect(Era::values(), (array) $eras));

        return $values === [] ? Era::all() : $values;
    }
}
