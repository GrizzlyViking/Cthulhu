<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int                                                                                                        $id
 * @property string                                                                                                     $name
 * @property string                                                                                                     $description
 * @property array<int, string>                                                                                         $eras
 * @property array<int, array{multiplier: int, options: array<int, string>}>                                            $skill_points_formula
 * @property int                                                                                                        $credit_rating_min
 * @property int                                                                                                        $credit_rating_max
 * @property array<int, array{type: string, skill?: string, count?: int, options?: array<int, string>, label?: string}> $skills
 */
class Occupation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'eras',
        'skill_points_formula',
        'credit_rating_min',
        'credit_rating_max',
        'skills',
    ];

    protected function casts(): array
    {
        return [
            'eras'                 => 'array',
            'skill_points_formula' => 'array',
            'skills'               => 'array',
            'credit_rating_min'    => 'integer',
            'credit_rating_max'    => 'integer',
        ];
    }

    /**
     * Occupation skill points derived from the character's characteristics.
     * Each formula component allows one or more characteristics; where a
     * choice exists (e.g. "EDU x2 + STR x2 or DEX x2") the highest applies.
     */
    public function skillPointsFor(Character $character): int
    {
        return collect($this->skill_points_formula)
            ->sum(function (array $component) use ($character): int {
                $best = collect($component['options'])
                    ->map(fn (string $characteristic): int => (int) $character->{$characteristic})
                    ->max();

                return $component['multiplier'] * $best;
            });
    }

    /**
     * A human readable version of the formula, e.g. "EDU × 2 + STR or DEX × 2".
     */
    public function formulaLabel(): string
    {
        $abbreviations = [
            'strength'     => 'STR',
            'dexterity'    => 'DEX',
            'intelligence' => 'INT',
            'constitution' => 'CON',
            'appearance'   => 'APP',
            'power'        => 'POW',
            'size'         => 'SIZ',
            'education'    => 'EDU',
        ];

        return collect($this->skill_points_formula)
            ->map(function (array $component) use ($abbreviations): string {
                $options = collect($component['options'])
                    ->map(fn (string $characteristic): string => $abbreviations[$characteristic])
                    ->implode(' or ');

                return "{$options} × {$component['multiplier']}";
            })
            ->implode(' + ');
    }
}
