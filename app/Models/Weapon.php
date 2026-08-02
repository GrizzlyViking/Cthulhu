<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Weapon extends Model
{
    use HasFactory;

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
        'impale',
    ];

    protected $appends = ['magazine_capacity'];

    protected function casts(): array
    {
        return [
            'impale' => 'boolean',
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
