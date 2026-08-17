<?php

namespace App\Models;

use App\Enums\Era;
use App\Misc\Money;
use App\Models\Concerns\HasEras;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string        $era            the handbook's availability cell, verbatim
 * @property array<string> $eras           the same thing the app can filter on
 * @property string        $name
 * @property string        $bullets_in_mag
 * @property-read ?int     $magazine_capacity rounds the magazine holds, null when it takes none
 * @property-read MorphPivot $pivot            the `equipables` row, when read off a character
 */
class Weapon extends Model
{
    use HasEras, HasFactory, SoftDeletes;

    protected $with = ['skills'];

    protected $fillable = [
        'name',
        'category',
        'skill',
        'damage',
        'base_range',
        'uses_per_round',
        'bullets_in_mag',
        'cost',
        'malfunction',
        'era',
        'eras',
        'impale',
    ];

    protected $appends = ['magazine_capacity', 'prices'];

    protected function casts(): array
    {
        return [
            'impale' => 'boolean',
            'eras'   => 'array',
        ];
    }

    public function characters(): MorphToMany
    {
        return $this->morphToMany(Character::class, 'equipable');
    }

    public function skills(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill', 'slug');
    }

    /**
     * What the book says it costs, one figure per era.
     *
     * `cost` is the printed cell and stays that way ("$7/$75", "-/$300"): it is
     * the 1920s price and the modern one with a slash between them. This is the
     * same cell read as numbers, so the sheet can fill in a price when the
     * weapon is bought. A dash on one side means the weapon did not exist then,
     * and answers null.
     */
    protected function prices(): Attribute
    {
        return Attribute::get(fn (): array => array_reduce(
            Era::cases(),
            function (array $prices, Era $era): array {
                $prices[$era->value] = Money::fromCostCell($this->cost, $era);

                return $prices;
            },
            [],
        ));
    }

    /**
     * How many rounds the magazine holds, or null when the weapon carries no
     * ammunition at all.
     *
     * The book prints this column as free text: a plain count ("6"), a choice
     * of magazines ("20/30/32", where the first is taken), a quantity with
     * words around it ("25 Squirts", "At least 10"), single-use notes
     * ("One Use", "1 only") or something uncountable ("Varies", "Separate",
     * "Auto-magazine", "-").
     */
    protected function magazineCapacity(): Attribute
    {
        return Attribute::get(function (): ?int {
            $printed = trim((string) $this->bullets_in_mag);

            if (preg_match('/\d+/', $printed, $match) === 1) {
                return (int) $match[0];
            }

            return str_contains(strtolower($printed), 'one use') ? 1 : null;
        });
    }
}
