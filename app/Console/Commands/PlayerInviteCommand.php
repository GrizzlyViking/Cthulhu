<?php

namespace App\Console\Commands;

use App\Actions\SendInvitation;
use App\Console\Commands\Concerns\ResolvesPlayersAndGroups;
use App\Enums\RoleEnum;
use App\Exceptions\UserAlreadyExistsException;
use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class PlayerInviteCommand extends Command
{
    use ResolvesPlayersAndGroups;

    protected $signature = 'player:invite {email? : The email address to invite} {group? : The group to invite them into (name or id)}';

    protected $description = 'Invite a new player into a group by email';

    public function handle(SendInvitation $sendInvitation): int
    {
        $email = $this->argument('email') ?? text(
            label: 'Email address to invite',
            required: true,
            validate: fn (string $value): ?string => filter_var($value, FILTER_VALIDATE_EMAIL) === false
                ? 'Please enter a valid email address.'
                : null,
        );

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->error("[{$email}] is not a valid email address.");

            return self::FAILURE;
        }

        $group = $this->resolveGroup($this->argument('group'));

        if ($group === null) {
            return self::FAILURE;
        }

        /** @var ?User $inviter the first admin, or nobody — invited_by is nullable */
        $inviter = User::query()->role(RoleEnum::ADMIN->value)->orderBy('id')->first();

        try {
            $invitation = $sendInvitation->send($email, $group, $inviter);
        } catch (UserAlreadyExistsException) {
            $this->error("A user with the email [{$email}] already exists. Use player:assign to move them into a group.");

            return self::FAILURE;
        }

        $this->info("Invitation emailed to [{$email}] for group [{$group->name}] (expires {$invitation->expires_at->toDateString()}).");
        $this->line('Accept URL (for manual delivery): '.route('invitation.show', $invitation->token));

        return self::SUCCESS;
    }
}
