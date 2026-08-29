<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;

class CharacterPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Character $character): bool
    {
        if ($character->isNpc()) {
            return $this->conjuredBy($user, $character);
        }

        return $user->id === $character->user_id || $this->sharesGroupWith($user, $character);
    }

    public function update(User $user, Character $character): bool
    {
        if ($character->isNpc()) {
            return $this->conjuredBy($user, $character);
        }

        return $user->id === $character->user_id
            || ($user->hasRole('keeper') && $this->sharesGroupWith($user, $character));
    }

    public function patch(User $user, Character $character): bool
    {
        if ($character->isNpc()) {
            return $this->conjuredBy($user, $character);
        }

        return $user->id === $character->user_id
            || ($user->hasRole('keeper') && $this->sharesGroupWith($user, $character));
    }

    public function delete(User $user, Character $character): bool
    {
        if ($character->isNpc()) {
            return $this->conjuredBy($user, $character);
        }

        return $user->id === $character->user_id || $user->hasRole('admin');
    }

    /**
     * Bringing a deleted sheet back, and finishing one off for good, answer to
     * whoever could delete it in the first place — there is no third party with
     * a claim on either. The Keeper's cast is its own Keeper's business as ever.
     */
    public function restore(User $user, Character $character): bool
    {
        return $this->delete($user, $character);
    }

    public function forceDelete(User $user, Character $character): bool
    {
        return $this->delete($user, $character);
    }

    public function assignUser(User $user, Character $character): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Anything beyond a player's own character requires both sides to sit in
     * the same, non-null group — keepers included, they are per-group.
     */
    private function sharesGroupWith(User $user, Character $character): bool
    {
        return $user->group_id !== null && $user->group_id === $character->group_id;
    }

    /**
     * The Keeper's cast answers to its own Keeper and to nobody else — not the
     * players, not another Keeper of the same group, not an admin. A cultist with
     * a knife and a secret is the one thing at the table that must stay hidden, so
     * this is deliberately narrower than every other rule above.
     */
    private function conjuredBy(User $user, Character $character): bool
    {
        return $character->keeper_id !== null && $character->keeper_id === $user->id;
    }
}
