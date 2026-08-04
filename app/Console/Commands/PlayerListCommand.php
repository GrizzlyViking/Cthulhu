<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\ResolvesPlayersAndGroups;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Console\Command;

class PlayerListCommand extends Command
{
    use ResolvesPlayersAndGroups;

    protected $signature = 'player:list {group? : Only list players of this group (name or id)}';

    protected $description = 'List players with their group, blocked state and character count';

    public function handle(): int
    {
        $query = User::query()->with('group')->withCount('characters')->orderBy('name');

        if (($identifier = $this->argument('group')) !== null) {
            $group = $this->resolveGroup($identifier);

            if ($group === null) {
                return self::FAILURE;
            }

            $query->where('group_id', $group->id);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('No players found.');

            return self::SUCCESS;
        }

        $pendingEmails = Invitation::query()->pending()->pluck('email')->all();

        $this->table(
            ['Name', 'Email', 'Group', 'Blocked', 'Characters', 'Pending invite'],
            $users->map(fn (User $user): array => [
                $user->name,
                $user->email,
                $user->group?->name ?? '-',
                $user->isBlocked() ? 'Yes' : 'No',
                $user->characters_count,
                in_array($user->email, $pendingEmails, true) ? 'Yes' : 'No',
            ]),
        );

        return self::SUCCESS;
    }
}
