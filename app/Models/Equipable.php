<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * One thing an investigator owns: the row joining a character to a weapon or a
 * piece of equipment, carrying the ammunition, the quantity, the note and the
 * place it is kept.
 *
 * The relations normally reach this through the pivot rather than the model —
 * it exists so the storage locations can be counted and so a place cannot be
 * removed while something is still in it.
 *
 * @property int     $id
 * @property int     $character_id
 * @property string  $equipable_type
 * @property int     $equipable_id
 * @property ?int    $storage_location_id
 * @property int     $quantity
 * @property ?string $notes
 */
class Equipable extends Model
{
    protected $table = 'equipables';

    protected $fillable = [
        'character_id',
        'equipable_type',
        'equipable_id',
        'storage_location_id',
        'quantity',
        'notes',
        'ammo',
        'ammo_reserve',
    ];

    public function equipable(): MorphTo
    {
        return $this->morphTo();
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class);
    }
}
