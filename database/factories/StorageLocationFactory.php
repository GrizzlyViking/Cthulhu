<?php

namespace Database\Factories;

use App\Models\StorageLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StorageLocation>
 */
class StorageLocationFactory extends Factory
{
    protected $model = StorageLocation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(2, true));

        return [
            'slug'     => Str::slug($name),
            'name'     => $name,
            'order_by' => 0,
        ];
    }
}
