<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\ResolvesPlayersAndGroups;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;

class PlayerAssignCommand extends Command
{
    use ResolvesPlayersAndGroups;

    protected $signature = 'player:assign {email? : The email of an existing user} {group? : The group to move them into (name or id)}';

    protected $description = 'Move an existing user (and their characters) into a group';

    public function handle(): int
    {
        $user = $this->resolveUser($this->argument('email'));

        if ($user === null) {
            return self::FAILURE;
        }

        $group = $this->resolveGroup($this->argument('group'));

        if ($group === null) {
            return self::FAILURE;
        }

        if ($user->group_id === $group->id) {
            $this->info("[{$user->email}] already belongs to group [{$group->name}]. Nothing to do.");

            return self::SUCCESS;
        }

        if ($user->group_id !== null) {
            $this->warn("[{$user->email}] currently belongs to group [{$user->group->name}].");

            if (! confirm(label: "Move them (and their characters) to [{$group->name}]?")) {
                $this->info('No changes made.');

                return self::SUCCESS;
            }
        }

        $user->update(['group_id' => $group->id]);
        $moved = $user->characters()->withTrashed()->update(['group_id' => $group->id]);

        $this->info("[{$user->email}] assigned to group [{$group->name}] ({$moved} character(s) moved along).");

        return self::SUCCESS;
    }
}
