<?php

namespace App\Models;

use App\Enums\Era;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * A campaign a group plays.
 *
 * The era belongs here rather than to the group: a table that finishes a 1920s
 * campaign and starts a modern one is the whole reason previous games are kept
 * around. The group's own era is only the default a new game is born with.
 *
 * @property int    $id
 * @property int    $group_id
 * @property string $name
 * @property Era    $era
 */
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory;

    protected $fillable = ['group_id', 'name', 'era'];

    protected function casts(): array
    {
        return [
            'era' => Era::class,
        ];
    }

    /**
     * @return BelongsTo<Group, $this>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return BelongsToMany<Character, $this>
     */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)->withTimestamps();
    }

    /**
     * Whether this is the game its group is currently playing. The group's
     * pointer is the only authority, so two games can never both say yes.
     */
    public function isActive(): bool
    {
        return $this->group?->active_game_id === $this->id;
    }

    /**
     * Make this the game its group is playing, standing down whichever was.
     */
    public function activate(): void
    {
        Group::query()->whereKey($this->group_id)->update(['active_game_id' => $this->id]);

        $this->unsetRelation('group');
    }
}
