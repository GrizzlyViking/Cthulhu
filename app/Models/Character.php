<?php

namespace App\Models;

use App\Misc\CharacterCreation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $strength
 * @property int $dexterity
 * @property int intelligence
 * @property int $constitution
 * @property int $appearance
 * @property int $power
 * @property int $size
 * @property int $education
 * @property int $move_rate
 * @property int $hit_points
 * @property int $sanity
 * @property int $luck
 * @property int $magic_points
 * @property int $dodge
 * @property int $build
 * @property string $damage_bonus
 * @property string $avatar
 * @property boolean $temporary_insanity
 * @property boolean $indefinite_insanity
 * @property boolean $major_wound
 * @property boolean $unconscious
 * @property boolean $dying
 */
class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'occupation',
        'age',
        'gender',
        'residence',
        'birthplace',
        'avatar',
    ];

    protected $with = ['skills', 'player', 'weapons'];

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
                    'value' => $skill->starting_value
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
                'value' => $skill->starting_value
            ]);
        });
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot('value', 'experience', 'order')->orderBy('slug');
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
}
