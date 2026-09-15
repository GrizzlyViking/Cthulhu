<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $game_id
 * @property string $name
 * @property string $path
 * @property string $mime_type
 * @property int    $size
 * @property Game   $game
 */
class GameResource extends Model
{
    protected $fillable = ['game_id', 'name', 'path', 'mime_type', 'size'];

    protected $hidden = ['path'];

    protected function casts(): array
    {
        return ['size' => 'integer'];
    }

    /** @return BelongsTo<Game, $this> */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
