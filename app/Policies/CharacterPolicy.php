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
        return $user->id === $character->user_id || $this->sharesGroupWith($user, $character);
    }

    public function update(User $user, Character $character): bool
    {
        return $user->id === $character->user_id
            || ($user->hasRole('keeper') && $this->sharesGroupWith($user, $character));
    }

    public function patch(User $user, Character $character): bool
    {
        return $user->id === $character->user_id
            || ($user->hasRole('keeper') && $this->sharesGroupWith($user, $character));
    }

    public function delete(User $user, Character $character): bool
    {
        return $user->id === $character->user_id || $user->hasRole('admin');
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
}
