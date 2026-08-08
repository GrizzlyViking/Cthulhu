<?php

namespace Database\Seeders;

use App\Misc\EraTable;
use App\Misc\WeaponTable;
use App\Models\Weapon;
use Illuminate\Database\Seeder;

class WeaponSeeder extends Seeder
{
    /**
     * The Investigator Handbook weapons table, pp. 250–254.
     *
     * Keyed on the name so it can be re-run over a database the sync migration
     * has already filled in without doubling every row.
     */
    public function run(): void
    {
        foreach (WeaponTable::all() as $weapon) {
            Weapon::updateOrCreate(
                ['name' => $weapon['name']],
                [...$weapon, 'eras' => EraTable::forWeapon($weapon['era'], $weapon['name'])],
            );
        }

        // Not from the book: unarmed brawling still needs something to equip.
        Weapon::updateOrCreate(['name' => 'Brawl (Unarmed)'], [
            'category'       => WeaponTable::HAND_TO_HAND,
            'skill'          => 'fighting-brawl',
            'damage'         => '1D3+DB',
            'base_range'     => 'Touch',
            'uses_per_round' => '1',
            'bullets_in_mag' => '-',
            'cost'           => '-',
            'malfunction'    => '-',
            'era'            => '1920s, Modern',
            'eras'           => EraTable::forWeapon('1920s, Modern'),
            'impale'         => false,
        ]);
    }
}
