<?php

namespace Database\Factories;

use App\Misc\EquipmentTable;
use App\Models\EquipmentItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EquipmentItem>
 */
class EquipmentItemFactory extends Factory
{
    protected $model = EquipmentItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucwords(fake()->unique()->words(2, true));

        return [
            'slug'      => Str::slug($name),
            'name'      => $name,
            'section'   => fake()->randomElement(EquipmentTable::sections()),
            'cost'      => '$'.fake()->randomFloat(2, 0.1, 99),
            'era'       => '1920s',
            'is_custom' => false,
        ];
    }

    /**
     * Something a player typed rather than something the book prints.
     */
    public function custom(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_custom' => true,
            'section'   => null,
            'cost'      => null,
            'slug'      => 'custom--'.$attributes['slug'],
        ]);
    }
}
