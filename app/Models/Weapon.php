<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Weapon extends Model
{
    use HasFactory;

    protected $with = ['skills'];

    protected $fillable = [];

    public function characters(): MorphToMany
    {
        return $this->morphToMany(Character::class, 'equipable');
    }

    public function skills(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill', 'slug');
    }
}
