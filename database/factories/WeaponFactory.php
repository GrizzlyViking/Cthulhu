<?php

namespace Database\Factories;

use App\Enums\Era;
use App\Misc\WeaponTable;
use App\Models\Weapon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Weapon>
 */
class WeaponFactory extends Factory
{
    protected $model = Weapon::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => ucwords(fake()->unique()->words(2, true)),
            'category'       => fake()->randomElement(WeaponTable::categories()),
            'skill'          => 'firearms-handgun',
            'damage'         => '1D10',
            'base_range'     => '15 yards',
            'uses_per_round' => '1',
            'bullets_in_mag' => '6',
            'cost'           => '$25 / $400',
            'malfunction'    => '100',
            'era'            => fake()->randomElement(Era::cases())->value,
            'eras'           => Era::all(),
            'impale'         => true,
        ];
    }
}
