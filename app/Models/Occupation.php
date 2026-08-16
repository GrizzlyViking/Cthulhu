<?php

namespace App\Models;

use App\Models\Concerns\HasEras;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int                                                                                                               $id
 * @property string                                                                                                            $name
 * @property string                                                                                                            $description
 * @property bool                                                                                                              $is_custom
 * @property ?int                                                                                                              $created_by
 * @property array<int, string>                                                                                                $eras
 * @property array<int, array{multiplier: int, options: array<int, string>}>                                                   $skill_points_formula
 * @property int                                                                                                               $credit_rating_min
 * @property int                                                                                                               $credit_rating_max
 * @property array<int, string|array{type: string, skill?: string, count?: int, options?: array<int, string>, label?: string}> $skills               a plain slug, or a choice the player (or the generator) settles
 * @property ?Carbon                                                                                                           $deleted_at
 */
class Occupation extends Model
{
    use HasEras, SoftDeletes;

    /**
     * The characteristics an occupation's skill point formula may draw on,
     * keyed by the column they read and valued by the book's abbreviation.
     *
     * @var array<string, string>
     */
    public const CHARACTERISTICS = [
        'strength'     => 'STR',
        'constitution' => 'CON',
        'size'         => 'SIZ',
        'dexterity'    => 'DEX',
        'appearance'   => 'APP',
        'intelligence' => 'INT',
        'power'        => 'POW',
        'education'    => 'EDU',
    ];

    /**
     * Skills an occupation may never list.
     *
     * Cthulhu Mythos takes no skill points at all, and Credit Rating takes them
     * from every occupation and is bounded by the range instead — see
     * {@see \App\Http\Requests\Wizard\WizardSkillsRequest}. Listing either would
     * offer the player points the next step then refuses.
     *
     * @var array<int, string>
     */
    public const UNSELECTABLE_SKILLS = ['credit_rating', 'cthulhu_mythos'];

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'is_custom',
        'created_by',
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
            'is_custom'            => 'boolean',
            'credit_rating_min'    => 'integer',
            'credit_rating_max'    => 'integer',
        ];
    }

    /**
     * The player who contributed it, where one did. Null for the book's own,
     * and for anything whose author has since left.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The investigators who trained as this. A retired occupation keeps them —
     * the foreign key is untouched by the soft delete.
     *
     * @return HasMany<Character, $this>
     */
    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
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
        return collect($this->skill_points_formula)
            ->map(function (array $component): string {
                $options = collect($component['options'])
                    ->map(fn (string $characteristic): string => self::CHARACTERISTICS[$characteristic] ?? strtoupper($characteristic))
                    ->implode(' or ');

                return "{$options} × {$component['multiplier']}";
            })
            ->implode(' + ');
    }

    /**
     * The occupation as the wizard and the admin pages want it: everything on
     * the row plus the formula spelled out.
     *
     * @return array<string, mixed>
     */
    public function toWizardArray(): array
    {
        return [
            ...$this->toArray(),
            'formula_label' => $this->formulaLabel(),
        ];
    }
}
