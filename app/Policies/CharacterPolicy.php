<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;

class CharacterPolicy
{
    public function view(User $user, Character $character): bool
    {
        return true;
    }

    public function update(User $user, Character $character): bool
    {
        return $user->id === $character->user_id || $user->hasRole('keeper');
    }

    public function delete(User $user, Character $character)
    {
        return $user->id === $character->user_id || $user->hasRole('admin');
    }

    public function assignUser(User $user, Character $character): bool
    {
        return $user->hasRole('admin');
    }
}
