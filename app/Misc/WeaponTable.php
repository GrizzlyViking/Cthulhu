<?php

namespace App\Misc;

/**
 * The Call of Cthulhu 7th edition weapons table, transcribed from the
 * Investigator Handbook, pages 250–254.
 *
 * The `cost` cell is kept verbatim from the book ("1920s/modern"), because a
 * good number of entries only carry a price in one of the two eras. `impale`
 * comes from the "(i)" marker, which the book applies either to a single row
 * or to a whole section heading (handguns, rifles, assault rifles, submachine
 * guns, machine guns, explosives).
 */
class WeaponTable
{
    public const HAND_TO_HAND = 'Hand-to-Hand';

    public const HANDGUNS = 'Handguns';

    public const RIFLES = 'Rifles';

    public const SHOTGUNS = 'Shotguns';

    public const ASSAULT_RIFLES = 'Assault Rifles';

    public const SUBMACHINE_GUNS = 'Submachine Guns';

    public const MACHINE_GUNS = 'Machine Guns';

    public const HEAVY = 'Explosives, Heavy Weapons, Misc.';

    /**
     * Section order as printed, used to group the weapon picker.
     *
     * @return list<string>
     */
    public static function categories(): array
    {
        return [
            self::HAND_TO_HAND,
            self::HANDGUNS,
            self::RIFLES,
            self::SHOTGUNS,
            self::ASSAULT_RIFLES,
            self::SUBMACHINE_GUNS,
            self::MACHINE_GUNS,
            self::HEAVY,
        ];
    }

    /**
     * Skills the weapons table refers to, keyed by slug.
     *
     * Only used to top up the skill list — existing rows keep their own
     * starting values.
     *
     * @return array<string, array{display_name: string, starting_value: int}>
     */
    public static function skills(): array
    {
        return [
            'fighting-brawl'        => ['display_name' => 'Fighting (Brawl)', 'starting_value' => 25],
            'fighting-whip'         => ['display_name' => 'Fighting (Whip)', 'starting_value' => 5],
            'fighting-chainsaw'     => ['display_name' => 'Fighting (Chainsaw)', 'starting_value' => 10],
            'fighting-axe'          => ['display_name' => 'Fighting (Axe)', 'starting_value' => 15],
            'fighting-garrote'      => ['display_name' => 'Fighting (Garrote)', 'starting_value' => 15],
            'fighting-flail'        => ['display_name' => 'Fighting (Flail)', 'starting_value' => 10],
            'fighting-spear'        => ['display_name' => 'Fighting (Spear)', 'starting_value' => 20],
            'fighting-sword'        => ['display_name' => 'Fighting (Sword)', 'starting_value' => 20],
            'firearms-bow'          => ['display_name' => 'Firearms (Bow)', 'starting_value' => 15],
            'firearms-handgun'      => ['display_name' => 'Firearms (Handgun)', 'starting_value' => 20],
            'firearms-rifle'        => ['display_name' => 'Firearms (Rifle)', 'starting_value' => 25],
            'firearms-shotgun'      => ['display_name' => 'Firearms (Shotgun)', 'starting_value' => 25],
            'firearms-smg'          => ['display_name' => 'Firearms (SMG)', 'starting_value' => 15],
            'firearms-mg'           => ['display_name' => 'Firearms (MG)', 'starting_value' => 10],
            'firearms-heavy'        => ['display_name' => 'Firearms (Heavy Weapons)', 'starting_value' => 10],
            'firearms-flamethrower' => ['display_name' => 'Firearms (Flamethrower)', 'starting_value' => 10],
            'throw'                 => ['display_name' => 'Throw', 'starting_value' => 20],
            'demolitions'           => ['display_name' => 'Demolitions', 'starting_value' => 1],
            'artillery'             => ['display_name' => 'Artillery', 'starting_value' => 1],
            'electric_repair'       => ['display_name' => 'Electric Repair', 'starting_value' => 10],
        ];
    }

    /**
     * Names the first import produced, mapped onto the canonical ones.
     *
     * The original import kept the book's "(i)" and "*" notation inside the
     * name and truncated a few rows; those markers are now columns.
     *
     * @return array<string, string>
     */
    public static function renames(): array
    {
        return [
            'Club,small (nightstick)'                => 'Club, small (nightstick)',
            'Crossbow (i)'                           => 'Crossbow',
            'Garrote*(i)'                            => 'Garrote',
            'Hatchet/Sickle (i)'                     => 'Hatchet/Sickle',
            'Knife, Large (machete, etc.) (i)'       => 'Knife, Large (machete, etc.)',
            'Knife,Medium (carving knife, etc.) (i)' => 'Knife, Medium (carving knife, etc.)',
            'Knife, Small (switchblade, etc.) (i)'   => 'Knife, Small (switchblade, etc.)',
            'Shuriken (i)'                           => 'Shuriken',
            'Spear (cavalry lance)(i)'               => 'Spear (cavalry lance)',
            'Spear, Thrown (i)'                      => 'Spear, Thrown',
            'Bergmann MP181/MP2811'                  => 'Bergmann MP18I/MP28II',
            'Browning Auto'                          => 'Browning Auto Rifle M1918',
            'Vickers .303'                           => 'Vickers .303 Machine Gun',
        ];
    }

    /**
     * @return list<array{name: string, skill: string, damage: string, base_range: string, uses_per_round: string, bullets_in_mag: string, cost: string, malfunction: string, era: string, category: string, impale: bool}>
     */
    public static function all(): array
    {
        return [
            ...self::handToHand(),
            ...self::handguns(),
            ...self::rifles(),
            ...self::shotguns(),
            ...self::assaultRifles(),
            ...self::submachineGuns(),
            ...self::machineGuns(),
            ...self::heavyWeapons(),
        ];
    }

    /**
     * Investigator Handbook pp. 250–251.
     *
     * @return list<array<string, mixed>>
     */
    private static function handToHand(): array
    {
        return self::section(self::HAND_TO_HAND, [
            ['Bow and Arrows', 'firearms-bow', '1D6+half DB', '30 yards', '1', '1', '$7/$75', '97', '1920s, Modern', false],
            ['Brass Knuckles', 'fighting-brawl', '1D3+1+DB', 'Touch', '1', '-', '$1/$10', '-', '1920s, Modern', false],
            ['Bullwhip', 'fighting-whip', '1D3+half DB', '10 feet', '1', '-', '$5/$50', '-', '1920s', false],
            ['Burning Torch', 'fighting-brawl', '1D6+burn', 'Touch', '1', '-', '$0.05/$0.50', '-', '1920s, Modern', false],
            ['Chainsaw', 'fighting-chainsaw', '2D8', 'Touch', '1', '-', '-/$300', '95', 'Modern', true],
            ['Blackjack (Cosh, life-preserver)', 'fighting-brawl', '1D8+DB', 'Touch', '1', '-', '$2/$15', '-', '1920s, Modern', false],
            ['Club, large (baseball, cricket bat, poker)', 'fighting-brawl', '1D8+DB', 'Touch', '1', '-', '$3/$35', '-', '1920s, Modern', false],
            ['Club, small (nightstick)', 'fighting-brawl', '1D6+DB', 'Touch', '1', '-', '$3/$35', '-', '1920s, Modern', false],
            ['Crossbow', 'firearms-bow', '1D8+2', '50 yards', '1/2', '1', '$10/$100', '96', '1920s, Modern', true],
            ['Garrote', 'fighting-garrote', '1D6+DB', 'Touch', '1', '-', '$0.50/$3', '-', '1920s, Modern', true],
            ['Hatchet/Sickle', 'fighting-axe', '1D6+1+DB', 'Touch', '1', '-', '$3/$9', '-', '1920s, Modern', true],
            ['Knife, Large (machete, etc.)', 'fighting-brawl', '1D8+DB', 'Touch', '1', '-', '$4/$50', '-', '1920s, Modern', true],
            ['Knife, Medium (carving knife, etc.)', 'fighting-brawl', '1D4+2+DB', 'Touch', '1', '-', '$2/$15', '-', '1920s, Modern', true],
            ['Knife, Small (switchblade, etc.)', 'fighting-brawl', '1D4+DB', 'Touch', '1', '-', '$2/$6', '-', '1920s, Modern', true],
            ['Live Wire, 220-volt charge', 'fighting-brawl', '2D8+stun', 'Touch', '1', '-', '-', '95', 'Modern', false],
            ['Mace Spray', 'fighting-brawl', 'Stun', '6 feet', '1', '25 Squirts', '-/$10', '-', '1920s, Modern', false],
            ['Nunchaku', 'fighting-flail', '1D8+DB', 'Touch', '1', '-', '$1/$10', '-', '1920s, Modern', false],
            ['Rock, Thrown', 'throw', '1D4+half DB', 'STR/5 yards', '1', '-', '-', '-', '1920s, Modern', false],
            ['Shuriken', 'throw', '1D3+half DB', 'STR/5 yards', '2', 'One Use', '$0.50/$3', '100', '1920s, Modern', true],
            ['Spear (cavalry lance)', 'fighting-spear', '1D8+1', 'Touch', '1', '-', '$25/$150', '-', '1920s, Modern', true],
            ['Spear, Thrown', 'throw', '1D8+half DB', 'STR/5 yards', '1', '-', '$1/$25', '-', 'Rare', true],
            ['Sword, heavy (cavalry saber)', 'fighting-sword', '1D8+1+DB', 'Touch', '1', '-', '$30/$75', '-', '1920s, Modern', false],
            ['Sword, medium (rapier, heavy epee)', 'fighting-sword', '1D6+1+DB', 'Touch', '1', '-', '$15/$100', '-', '1920s, Modern', true],
            ['Sword, light (sharpened fencing foil, sword cane)', 'fighting-sword', '1D6+DB', 'Touch', '1', '-', '$25/$100', '-', '1920s, Modern', true],
            ['Taser (contact)', 'fighting-brawl', '1D3+stun', 'Touch', '1', 'Varies', '-/$200', '97', 'Modern', false],
            ['Taser (dart)', 'firearms-handgun', '1D3+stun', '15 feet', '1', '3', '-/$400', '95', 'Modern', false],
            ['War Boomerang', 'throw', '1D8+half DB', 'STR/5 yards', '1', '-', '$2/$4', '-', 'Rare', false],
            ['Wood Axe', 'fighting-axe', '1D8+2+DB', 'Touch', '1', '-', '$5/$10', '-', '1920s, Modern', true],
        ]);
    }

    /**
     * Investigator Handbook p. 251. The section heading carries "(i)".
     *
     * @return list<array<string, mixed>>
     */
    private static function handguns(): array
    {
        return self::section(self::HANDGUNS, [
            ['Flintlock', 'firearms-handgun', '1D6+1', '10 yards', '1/4', '1', '$30/$300', '95', 'Rare', true],
            ['.22 Short Automatic', 'firearms-handgun', '1D6', '10 yards', '1 (3)', '6', '$25/$190', '100', '1920s, Modern', true],
            ['.25 Derringer (1B)', 'firearms-handgun', '1D6', '3 yards', '1', '1', '$12/$55', '100', '1920s', true],
            ['.32 or 7.65mm Revolver', 'firearms-handgun', '1D8', '15 yards', '1 (3)', '6', '$15/$200', '100', '1920s, Modern', true],
            ['.32 or 7.65mm Automatic', 'firearms-handgun', '1D8', '15 yards', '1 (3)', '8', '$20/$350', '99', '1920s, Modern', true],
            ['.357 Magnum Revolver', 'firearms-handgun', '1D8+1D4', '15 yards', '1 (3)', '6', '-/$425', '100', 'Modern', true],
            ['.38 or 9mm Revolver', 'firearms-handgun', '1D10', '15 yards', '1 (3)', '6', '$25/$200', '100', '1920s, Modern', true],
            ['.38 Automatic', 'firearms-handgun', '1D10', '15 yards', '1 (3)', '8', '$30/$375', '99', '1920s, Modern', true],
            ['Beretta M9', 'firearms-handgun', '1D10', '15 yards', '1 (3)', '15', '-/$500', '98', 'Modern', true],
            ['Glock 17 9mm Auto', 'firearms-handgun', '1D10', '15 yards', '1 (3)', '17', '-/$500', '98', 'Modern', true],
            ['Model P08 Luger', 'firearms-handgun', '1D10', '15 yards', '1 (3)', '8', '$75/$600', '99', '1920s, Modern', true],
            ['.41 Revolver', 'firearms-handgun', '1D10', '15 yards', '1 (3)', '8', '$30/-', '100', '1920s, Rare', true],
            ['.44 Magnum Revolver', 'firearms-handgun', '1D10+1D4+2', '15 yards', '1 (3)', '6', '-/$475', '100', 'Modern', true],
            ['.45 Revolver', 'firearms-handgun', '1D10+2', '15 yards', '1 (3)', '6', '$30/$300', '100', '1920s, Modern', true],
            ['.45 Automatic', 'firearms-handgun', '1D10+2', '15 yards', '1 (3)', '7', '$40/$375', '100', '1920s, Modern', true],
            ['IMI Desert Eagle', 'firearms-handgun', '1D10+1D6+3', '15 yards', '1 (3)', '7', '-/$650', '94', 'Modern', true],
        ]);
    }

    /**
     * Investigator Handbook p. 252. The section heading carries "(i)".
     *
     * @return list<array<string, mixed>>
     */
    private static function rifles(): array
    {
        return self::section(self::RIFLES, [
            ['.58 Springfield Rifle Musket', 'firearms-rifle', '1D10+4', '60 yards', '1/4', '1', '$25/$350', '95', 'Rare', true],
            ['.22 Bolt-Action Rifle', 'firearms-rifle', '1D6+1', '30 yards', '1', '6', '$13/$70', '99', '1920s, Modern', true],
            ['.30 Lever-Action Carbine', 'firearms-rifle', '2D6', '50 yards', '1', '6', '$19/$150', '98', '1920s, Modern', true],
            ['.45 Martini-Henry Rifle', 'firearms-rifle', '1D8+1D6+3', '80 yards', '1/3', '1', '$20/$200', '100', '1920s', true],
            ["Col. Moran's Air Rifle", 'firearms-rifle', '2D6+1', '20 yards', '1/3', '1', '$200', '88', '1920s', true],
            ['Garand M1, M2 Rifle', 'firearms-rifle', '2D6+4', '110 yards', '1', '8', '$400', '100', 'WWII, Later', true],
            ['SKS Carbine', 'firearms-rifle', '2D6+1', '90 yards', '1 (2)', '10', '$500', '97', 'Modern', true],
            ['.303 Lee-Enfield', 'firearms-rifle', '2D6+4', '110 yards', '1', '10', '$50/$300', '100', '1920s, Modern', true],
            ['.30-06 Bolt-Action Rifle', 'firearms-rifle', '2D6+4', '110 yards', '1', '5', '$75/$175', '100', '1920s, Modern', true],
            ['.30-06 Semi-Automatic Rifle', 'firearms-rifle', '2D6+4', '110 yards', '1', '5', '$275', '100', 'Modern', true],
            ['.444 Marlin Rifle', 'firearms-rifle', '2D8+4', '110 yards', '1', '5', '$400', '98', 'Modern', true],
            ['Elephant Gun (2B)', 'firearms-rifle', '3D6+4', '100 yards', '1 or 2', '2', '$400/$1,800', '100', '1920s, Modern', true],
        ]);
    }

    /**
     * Investigator Handbook p. 252. Shotguns do not impale.
     *
     * @return list<array<string, mixed>>
     */
    private static function shotguns(): array
    {
        return self::section(self::SHOTGUNS, [
            ['20-gauge Shotgun (2B)', 'firearms-shotgun', '2D6/1D6/1D3', '10/20/50 yards', '1 or 2', '2', '$35/Rare', '100', '1920s', false],
            ['16-gauge Shotgun (2B)', 'firearms-shotgun', '2D6+2/1D6+1/1D4', '10/20/50 yards', '1 or 2', '2', '$40/Rare', '100', '1920s', false],
            ['12-gauge Shotgun (2B)', 'firearms-shotgun', '4D6/2D6/1D6', '10/20/50 yards', '1 or 2', '2', '$40/$200', '100', '1920s, Modern', false],
            ['12-gauge Shotgun (Pump)', 'firearms-shotgun', '4D6/2D6/1D6', '10/20/50 yards', '1', '5', '$45/$100', '100', 'Modern', false],
            ['12-gauge Shotgun (semi-auto)', 'firearms-shotgun', '4D6/2D6/1D6', '10/20/50 yards', '1 (2)', '5', '$45/$100', '100', 'Modern', false],
            ['12-gauge Shotgun (2B sawed off)', 'firearms-shotgun', '4D6/1D6', '5/10 yards', '1 or 2', '2', 'N/A', '100', '1920s', false],
            ['10-gauge Shotgun (2B)', 'firearms-shotgun', '4D6+2/2D6+1/1D4', '10/20/50 yards', '1 or 2', '2', 'Rare', '100', '1920s, Rare', false],
            ['12-gauge Benelli M3 (folding stock)', 'firearms-shotgun', '4D6/2D6/1D6', '10/20/50 yards', '1 (2)', '7', '-/$895', '100', 'Modern', false],
            ['12-gauge SPAS (folding stock)', 'firearms-shotgun', '4D6/2D6/1D6', '10/20/50 yards', '1', '8', '-/$600', '98', 'Modern', false],
        ]);
    }

    /**
     * Investigator Handbook p. 253. The section heading carries "(i)".
     *
     * @return list<array<string, mixed>>
     */
    private static function assaultRifles(): array
    {
        return self::section(self::ASSAULT_RIFLES, [
            ['AK-47 or AKM', 'firearms-rifle', '2D6+1', '100 yards', '1 (2) or full auto', '30', '-/$200', '100', 'Modern', true],
            ['AK-74', 'firearms-rifle', '2D6', '110 yards', '1 (2) or full auto', '30', '-/$1,000', '97', 'Modern', true],
            ['Barrett Model 82', 'firearms-rifle', '2D10+1D8+6', '250 yards', '1', '11', '-/$3,000', '96', 'Modern', true],
            ['FN FAL Light Automatic', 'firearms-rifle', '2D6+4', '110 yards', '1 (2) or burst 3', '20', '-/$1,500', '97', 'Modern', true],
            ['Galil Assault Rifle', 'firearms-rifle', '2D6', '110 yards', '1 or full auto', '20', '-/$2,000', '98', 'Modern', true],
            ['M16A2', 'firearms-rifle', '2D6', '110 yards', '1 (2) or burst 3', '30', 'N/A', '97', 'Modern', true],
            ['M4', 'firearms-rifle', '2D6', '90 yards', '1 or burst 3', '30', 'N/A', '97', 'Modern', true],
            ['Steyr AUG', 'firearms-rifle', '2D6', '110 yards', '1 (2) or full auto', '30', '-/$1,100', '99', 'Modern', true],
            ['Beretta M70/90', 'firearms-rifle', '2D6', '110 yards', '1 or full auto', '30', '-/$2,800', '99', 'Modern', true],
        ]);
    }

    /**
     * Investigator Handbook p. 253. The section heading carries "(i)".
     *
     * @return list<array<string, mixed>>
     */
    private static function submachineGuns(): array
    {
        return self::section(self::SUBMACHINE_GUNS, [
            ['Bergmann MP18I/MP28II', 'firearms-smg', '1D10', '20 yards', '1 (2) or full auto', '20/30/32', '$1,000/$20,000', '96', '1920s', true],
            ['Heckler & Koch MP5', 'firearms-smg', '1D10', '20 yards', '1 (2) or full auto', '15/30', 'N/A', '97', 'Modern', true],
            ['Ingram MAC-11', 'firearms-smg', '1D10', '15 yards', '1 (3) or full auto', '32', '-/$750', '96', 'Modern', true],
            ['Skorpion SMG', 'firearms-smg', '1D8', '15 yards', '1 (3) or full auto', '20', 'N/A', '96', 'Modern', true],
            ['Thompson', 'firearms-smg', '1D10+2', '20 yards', '1 or full auto', '20/30/50', '$200+/$1,600', '96', '1920s', true],
            ['Uzi SMG', 'firearms-smg', '1D10', '20 yards', '1 (2) or full auto', '32', '-/$1,000', '98', 'Modern', true],
        ]);
    }

    /**
     * Investigator Handbook p. 253. The section heading carries "(i)".
     *
     * @return list<array<string, mixed>>
     */
    private static function machineGuns(): array
    {
        return self::section(self::MACHINE_GUNS, [
            ['Model 1882 Gatling Gun', 'firearms-mg', '2D6+4', '100 yards', 'Full auto', '200', '$2,000/$14,000', '96', '1920s, Rare', true],
            ['Browning Auto Rifle M1918', 'firearms-mg', '2D6+4', '90 yards', '1 (2) or full auto', '20', '$800/$1,500', '100', '1920s', true],
            ['.30 Browning M1917A1', 'firearms-mg', '2D6+4', '150 yards', 'Full auto', '250', '$3,000/$30,000', '96', '1920s', true],
            ['Bren Gun', 'firearms-mg', '2D6+4', '110 yards', '1 or full auto', '30/100', '$3,000/$50,000', '96', '1920s', true],
            ['Mark I Lewis Gun', 'firearms-mg', '2D6+4', '110 yards', 'Full auto', '47/97', '$3,000/$20,000', '96', '1920s', true],
            ['Minigun', 'firearms-mg', '2D6+4', '200 yards', 'Full auto', '4000', 'N/A', '98', 'Modern', true],
            ['FN Minimi, 5.56mm', 'firearms-mg', '2D6', '110 yards', 'Full auto', '30/200', 'N/A', '99', 'Modern', true],
            ['Vickers .303 Machine Gun', 'firearms-mg', '2D6+4', '110 yards', 'Full auto', '250', 'N/A', '99', '1920s', true],
        ]);
    }

    /**
     * Investigator Handbook p. 254. The section heading carries "(i)".
     *
     * @return list<array<string, mixed>>
     */
    private static function heavyWeapons(): array
    {
        return self::section(self::HEAVY, [
            ['Molotov Cocktail', 'throw', '2D6+burn', 'STR/5 yards', '1/2', '1 only', 'N/A', '95', '1920s, Modern', true],
            ['Signal Handgun (Flare gun)', 'firearms-handgun', '1D10+1D3 burn', '10 yards', '1/2', '1', '$15/$75', '100', '1920s, Modern', true],
            ['M79 Grenade Launcher', 'firearms-heavy', '3D10/2 yards', '20 yards', '1/3', '1', 'N/A', '99', 'Modern', true],
            ['Dynamite Stick', 'throw', '4D10/3 yards', 'STR/5 yards', '1/2', '1 only', '$2/$5', '99', '1920s, Modern', true],
            ['Blasting Cap', 'electric_repair', '2D10/1 yard', 'N/A', 'N/A', 'One use', '$1/$20 box', '100', '1920s, Modern', true],
            ['Pipe Bomb', 'demolitions', '1D10/3 yards', 'In place', 'One use', '1 only', 'N/A', '95', '1920s, Modern', true],
            ['Plastique (C-4), 4 oz.', 'demolitions', '6D10/3 yards', 'In place', 'One use', '1 only', 'N/A', '99', 'Modern', true],
            ['Hand Grenade', 'throw', '4D10/3 yards', 'STR/5 yards', '1/2', '1 only', 'N/A', '99', '1920s, Modern', true],
            ['81mm Mortar', 'artillery', '6D10/6 yards', '500 yards', '1', 'Separate', 'N/A', '100', 'Modern', true],
            ['75mm Field Gun', 'artillery', '10D10/2 yards', '500 yards', '1/4', 'Separate', '$1,500/-', '99', '1920s, Modern', true],
            ['120mm Tank Gun (stabilized)', 'artillery', '15D10/4 yards', '2,000 yards', '1', 'Separate', 'N/A', '100', 'Modern', true],
            ['Ship-mounted 5-inch rifle, stabilized', 'artillery', '12D10/4 yards', '3,000 yards', '1', 'Auto-magazine', 'N/A', '98', 'Modern', true],
            ['Anti-Personnel Mine', 'demolitions', '4D10/5 yards', 'In place', 'In place', 'One use', 'N/A', '99', '1920s, Modern', true],
            ['Claymore Mine', 'demolitions', '6D6/20 yards', 'In place', 'In place', 'One use', 'N/A', '99', 'Modern', true],
            ['Flamethrower', 'firearms-flamethrower', '2D6+burn', '25 yards', '1', 'At least 10', 'N/A', '93', '1920s, Modern', true],
            ['LAW', 'firearms-heavy', '8D10/1 yard', '150 yards', '1', '1', 'N/A', '98', 'Modern', true],
        ]);
    }

    /**
     * Turn the compact positional rows above into keyed weapon rows.
     *
     * @param  list<array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string, 6: string, 7: string, 8: string, 9: bool}> $rows
     * @return list<array<string, mixed>>
     */
    private static function section(string $category, array $rows): array
    {
        return array_map(fn (array $row): array => [
            'name'           => $row[0],
            'skill'          => $row[1],
            'damage'         => $row[2],
            'base_range'     => $row[3],
            'uses_per_round' => $row[4],
            'bullets_in_mag' => $row[5],
            'cost'           => $row[6],
            'malfunction'    => $row[7],
            'era'            => $row[8],
            'impale'         => $row[9],
            'category'       => $category,
        ], $rows);
    }
}
