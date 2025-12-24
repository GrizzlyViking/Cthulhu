<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $slug
 * @property string $display_name
 * @property string $description
 * @property int    $starting_value
 */
class Skill extends Model
{
    protected $fillable = [
        'slug',
        'display_name',
        'description',
        'starting_value',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public $timestamps = false;
}
