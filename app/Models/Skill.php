<?php

namespace App\Models;

use App\Models\Concerns\HasEras;
use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int           $id
 * @property string        $slug
 * @property string        $display_name
 * @property string        $description
 * @property int           $starting_value
 * @property int           $order_by
 * @property array<string> $eras
 * @property ?Carbon       $deleted_at
 */
class Skill extends Model
{
    /** @use HasFactory<SkillFactory> */
    use HasEras, HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'display_name',
        'description',
        'starting_value',
        'order_by',
        'eras',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'eras' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * The characters carrying this skill. A retired skill keeps its pivot rows,
     * so this still counts them and restoring puts the skill back on those
     * sheets.
     */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)
            ->withPivot(['value', 'experience', 'order', 'show']);
    }

    /**
     * Where a newly created skill sorts on a character sheet: after everything
     * that already exists.
     */
    public static function nextOrder(): int
    {
        return (int) static::withTrashed()->max('order_by') + 1;
    }
}
