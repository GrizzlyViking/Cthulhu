<?php

namespace App\Console\Commands\Concerns;

use App\Models\Group;
use App\Models\User;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

/**
 * Shared lookups for the management commands: users are addressed by email,
 * groups by name or id, and both prompt interactively when the argument is
 * omitted. On failure the resolver prints the error line and returns null so
 * the command can simply bail out with a failure exit code.
 */
trait ResolvesPlayersAndGroups
{
    protected function resolveUser(?string $email): ?User
    {
        $email ??= text(label: 'Player email', required: true);

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            $this->error("No user found with email [{$email}].");
        }

        return $user;
    }

    protected function resolveGroup(?string $identifier): ?Group
    {
        if ($identifier === null) {
            if (Group::query()->doesntExist()) {
                $this->error('No groups exist yet. Create one with group:create.');

                return null;
            }

            $id = select(
                label: 'Group',
                options: Group::query()->orderBy('name')->pluck('name', 'id')->all(),
            );

            return Group::query()->find($id);
        }

        $group = Group::query()->where('name', $identifier)->first();

        if ($group === null && ctype_digit($identifier)) {
            $group = Group::query()->find((int) $identifier);
        }

        if ($group === null) {
            $this->error("No group found with name or id [{$identifier}].");
        }

        return $group;
    }
}
