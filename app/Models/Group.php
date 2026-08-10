<?php

namespace App\Models;

use App\Enums\Era;
use Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 * @property Era    $era            the era a new game is born with
 * @property ?int   $active_game_id
 */
class Group extends Model
{
    /** @use HasFactory<GroupFactory> */
    use HasFactory;

    protected $fillable = ['name', 'era', 'active_game_id'];

    protected function casts(): array
    {
        return [
            'era' => Era::class,
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    /**
     * @return HasMany<Game, $this>
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    /**
     * The campaign this group is playing right now. A group has at most one —
     * the pointer is on the group precisely so that stays true — and may have
     * none, between campaigns or before the first is made.
     *
     * @return BelongsTo<Game, $this>
     */
    public function activeGame(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'active_game_id');
    }

    /**
     * Start a campaign. The first one a group makes becomes the one it plays,
     * so a group is never left with games but nothing active by accident.
     */
    public function startGame(string $name, ?Era $era = null): Game
    {
        $game = $this->games()->create([
            'name' => $name,
            // A game always has an era. The group's is only the default, and
            // the Twenties stand in for a group that somehow has none.
            'era' => $era ?? $this->era ?? Era::Twenties,
        ]);

        if ($this->active_game_id === null) {
            $this->update(['active_game_id' => $game->id]);
        }

        return $game;
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Invitations that have neither been accepted nor expired.
     */
    public function pendingInvitations(): HasMany
    {
        return $this->invitations()->pending();
    }
}
