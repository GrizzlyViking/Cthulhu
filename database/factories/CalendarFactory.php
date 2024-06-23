<?php

namespace Database\Factories;

use App\Models\Calendar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'slug' => Str::slug($this->faker->sentence(2)),
        ];
    }
}
