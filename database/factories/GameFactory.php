<?php

namespace Database\Factories;

use App\Enums\Era;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'name'     => fake()->unique()->words(3, true),
            'era'      => Era::Twenties->value,
        ];
    }

    public function modern(): self
    {
        return $this->state(fn (): array => ['era' => Era::Modern->value]);
    }
}
