<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class GroupListCommand extends Command
{
    protected $signature = 'group:list';

    protected $description = 'List all groups with their member and pending invitation counts';

    public function handle(): int
    {
        $groups = Group::query()
            ->withCount([
                'users',
                'invitations as pending_invitations_count' => fn (Builder $query) => $query->pending(),
            ])
            ->orderBy('name')
            ->get();

        if ($groups->isEmpty()) {
            $this->info('No groups exist yet. Create one with group:create.');

            return self::SUCCESS;
        }

        $this->table(
            ['Id', 'Name', 'Era', 'Users', 'Pending invitations'],
            $groups->map(fn (Group $group): array => [
                $group->id,
                $group->name,
                $group->era->value,
                $group->users_count,
                $group->pending_invitations_count,
            ]),
        );

        return self::SUCCESS;
    }
}
