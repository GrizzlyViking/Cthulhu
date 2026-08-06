<?php

namespace App\Models;

use Database\Factories\StorageLocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Where an investigator keeps a thing. Players may add their own — a saddlebag,
 * a hotel safe — so this is a table rather than an enum.
 *
 * @property int     $id
 * @property string  $slug
 * @property string  $name
 * @property int     $order_by
 * @property ?Carbon $deleted_at
 */
class StorageLocation extends Model
{
    /** @use HasFactory<StorageLocationFactory> */
    use HasFactory, SoftDeletes;

    /**
     * What every server starts with, in the order they are offered.
     *
     * @var array<int, string>
     */
    public const array STARTING_LOCATIONS = [
        'On person',
        'Luggage',
        'Backpack',
        'Travel chest',
    ];

    protected $fillable = [
        'slug',
        'name',
        'order_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Everything currently kept here, weapons and equipment alike.
     */
    public function equipables(): HasMany
    {
        return $this->hasMany(Equipable::class);
    }

    /**
     * Where a newly added location sorts: after everything already offered.
     */
    public static function nextOrder(): int
    {
        return (int) static::withTrashed()->max('order_by') + 1;
    }

    /**
     * A slug for a player-supplied name, unique against retired rows too.
     */
    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'location';
        $slug = $base;

        for ($suffix = 2; static::withTrashed()->where('slug', $slug)->exists(); $suffix++) {
            $slug = $base.'-'.$suffix;
        }

        return $slug;
    }
}
