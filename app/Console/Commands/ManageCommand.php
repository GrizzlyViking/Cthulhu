<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\select;

class ManageCommand extends Command
{
    protected $signature = 'cthulhu:manage';

    protected $description = 'Interactive menu for managing groups and players';

    /** @var array<string, string> menu label => command to dispatch */
    private const array MENU = [
        'Create group'    => 'group:create',
        'List groups'     => 'group:list',
        'List players'    => 'player:list',
        'Invite player'   => 'player:invite',
        'Assign player'   => 'player:assign',
        'Change password' => 'player:password',
        'Block player'    => 'player:block',
        'Unblock player'  => 'player:unblock',
    ];

    public function handle(): int
    {
        while (true) {
            $choice = select(
                label: 'What would you like to do?',
                options: [...array_keys(self::MENU), 'Exit'],
            );

            if ($choice === 'Exit') {
                $this->info('The stars are right. Farewell, Keeper.');

                return self::SUCCESS;
            }

            $this->call(self::MENU[$choice]);
        }
    }
}
