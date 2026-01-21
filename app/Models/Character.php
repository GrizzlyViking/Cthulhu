<?php

namespace App\Models;

use App\Misc\CharacterCreation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int    $id
 * @property int    $strength
 * @property int    $dexterity
 * @property int    $intelligence
 * @property int    $constitution
 * @property int    $appearance
 * @property int    $power
 * @property int    $size
 * @property int    $education
 * @property int    $move_rate
 * @property int    $hit_points
 * @property int    $sanity
 * @property int    $luck
 * @property int    $magic_points
 * @property int    $dodge
 * @property int    $build
 * @property string $damage_bonus
 * @property string $avatar
 * @property bool   $temporary_insanity
 * @property bool   $indefinite_insanity
 * @property bool   $major_wound
 * @property bool   $unconscious
 * @property bool   $dying
 */
class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'occupation',
        'age',
        'gender',
        'residence',
        'birthplace',
        'strength',
        'dexterity',
        'intelligence',
        'constitution',
        'appearance',
        'power',
        'size',
        'education',
        'move_rate',
        'hit_points',
        'sanity',
        'luck',
        'magic_points',
        'dodge',
        'build',
        'damage_bonus',
        'avatar',
        'temporary_insanity',
        'indefinite_insanity',
        'major_wound',
        'unconscious',
        'dying',
    ];

    protected $with = ['skills', 'player', 'weapons'];

    protected function casts(): array
    {
        return [
            'temporary_insanity'  => 'boolean',
            'indefinite_insanity' => 'boolean',
            'major_wound'         => 'boolean',
            'unconscious'         => 'boolean',
            'dying'               => 'boolean',
            'strength'            => 'integer',
            'dexterity'           => 'integer',
            'intelligence'        => 'integer',
            'constitution'        => 'integer',
            'appearance'          => 'integer',
            'power'               => 'integer',
            'size'                => 'integer',
            'education'           => 'integer',
            'move_rate'           => 'integer',
            'hit_points'          => 'integer',
            'sanity'              => 'integer',
            'luck'                => 'integer',
            'magic_points'        => 'integer',
            'dodge'               => 'integer',
            'build'               => 'integer',
        ];
    }

    public function moveRate(): Attribute
    {
        return Attribute::make(
            get: function () {
                $move = CharacterCreation::moveRate($this);

                if ($this->age > 40 && $this->age < 51) {
                    return $move - 1;
                }
                if ($this->age > 50 && $this->age < 61) {
                    return $move - 2;
                }
                if ($this->age > 60 && $this->age < 71) {
                    return $move - 3;
                }
                if ($this->age > 70 && $this->age < 81) {
                    return $move - 4;
                }
                if ($this->age > 80 && $this->age < 91) {
                    return $move - 5;
                }

                return $move;
            }
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function addAllSkills(): self
    {
        Skill::all()
            ->each(function (Skill $skill) {
                $this->skills()->attach($skill, [
                    'order' => $skill->order_by,
                    'value' => $skill->starting_value,
                ]);
            });

        return $this;
    }

    public function appendSkills(): Collection
    {
        return Skill::all()
            ->reject(function (Skill $skill) {
                return $this->skills->contains($skill);
            })
            ->each(function (Skill $skill) {
                $this->skills()->attach($skill, [
                    'order' => $skill->order_by,
                    'value' => $skill->starting_value,
                ]);
            });
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot('value', 'experience', 'order')->orderBy('display_name');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function weapons(): MorphToMany
    {
        return $this->morphedByMany(Weapon::class, 'equipable')->withPivot('id', 'ammo');
    }

    public function getDamageBonus(): string
    {
        return CharacterCreation::damageBonus($this);
    }

    public function group() {}
}
