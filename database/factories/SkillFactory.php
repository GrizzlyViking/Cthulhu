<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    protected $model = Skill::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucwords(fake()->unique()->words(2, true));

        return [
            'slug'           => Str::slug($name),
            'display_name'   => $name,
            'description'    => fake()->sentence(),
            'starting_value' => fake()->numberBetween(1, 25),
        ];
    }
}
