<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Misc\CharacterCreation;
use App\Misc\Roll;
use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character>
 */
class CharacterFactory extends Factory
{
    public function configure(): CharacterFactory
    {
        $callback = function (Character $character) {
            $character->move_rate    = CharacterCreation::moveRate($character);
            $character->dodge        = CharacterCreation::dodge($character);
            $character->hit_points   = CharacterCreation::hitPoints($character);
            $character->sanity       = CharacterCreation::sanity($character);
            $character->magic_points = CharacterCreation::magicPoints($character);
            $character->build        = CharacterCreation::build($character);
            $character->damage_bonus = CharacterCreation::damageBonus($character);

            $character->save();
            $character->addAllSkills();
        };

        return $this->afterCreating($callback);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => $name = fake()->name(),
            'slug'         => Str::slug($name),
            'user_id'      => User::factory()->create()->id,
            'occupation'   => fake()->jobTitle(),
            'residence'    => fake()->city(),
            'birthplace'   => fake()->city(),
            'luck'         => Roll::dice('(3d6)*5'),
            'age'          => fake()->randomNumber(2),
            'gender'       => fake()->randomElement([Gender::Male->name, Gender::Female->name]),
            'strength'     => $str = Roll::dice('(3d6)*5'),
            'dexterity'    => $dex = Roll::dice('(3d6)*5'),
            'intelligence' => Roll::dice('(2d6+6)*5'),
            'constitution' => Roll::dice('(3d6)*5'),
            'appearance'   => Roll::dice('(3d6)*5'),
            'power'        => Roll::dice('(3d6)*5'),
            'size'         => Roll::dice('(2d6+6)*5'),
            'education'    => Roll::dice('(2d6+6)*5'),
        ];
    }
}
