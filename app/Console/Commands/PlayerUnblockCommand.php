<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\ResolvesPlayersAndGroups;
use Illuminate\Console\Command;

class PlayerUnblockCommand extends Command
{
    use ResolvesPlayersAndGroups;

    protected $signature = 'player:unblock {email? : The email of the user to unblock}';

    protected $description = 'Allow a blocked user to log in again';

    public function handle(): int
    {
        $user = $this->resolveUser($this->argument('email'));

        if ($user === null) {
            return self::FAILURE;
        }

        if (! $user->isBlocked()) {
            $this->info("[{$user->email}] is not blocked. Nothing to do.");

            return self::SUCCESS;
        }

        $user->update(['blocked_at' => null]);

        $this->info("[{$user->email}] is unblocked and may log in again.");

        return self::SUCCESS;
    }
}
