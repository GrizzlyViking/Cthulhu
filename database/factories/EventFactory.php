<?php

namespace Database\Factories;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'calendar_id' => Calendar::factory()->create()->id,
            'start_at' => fake()->dateTimeBetween('-1 months'),
            'end_at' => fake()->dateTimeBetween('now', '+1 months'),
        ];
    }
}
