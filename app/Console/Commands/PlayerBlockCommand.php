<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\ResolvesPlayersAndGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PlayerBlockCommand extends Command
{
    use ResolvesPlayersAndGroups;

    protected $signature = 'player:block {email? : The email of the user to block}';

    protected $description = 'Block a user from logging in and terminate their active sessions';

    public function handle(): int
    {
        $user = $this->resolveUser($this->argument('email'));

        if ($user === null) {
            return self::FAILURE;
        }

        if ($user->isBlocked()) {
            $this->warn("[{$user->email}] is already blocked (since {$user->blocked_at->toDateTimeString()}).");
        } else {
            $user->update(['blocked_at' => now()]);
        }

        $sessions = DB::table('sessions')->where('user_id', $user->id)->delete();

        $this->info("[{$user->email}] is blocked ({$sessions} active session(s) terminated).");

        return self::SUCCESS;
    }
}
