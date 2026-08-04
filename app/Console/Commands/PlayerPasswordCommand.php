<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\ResolvesPlayersAndGroups;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;

class PlayerPasswordCommand extends Command
{
    use ResolvesPlayersAndGroups;

    protected $signature = 'player:password {email? : The email of the user} {--password= : The new password (prompted securely when omitted)}';

    protected $description = 'Set a new password for a user';

    public function handle(): int
    {
        $user = $this->resolveUser($this->argument('email'));

        if ($user === null) {
            return self::FAILURE;
        }

        $newPassword = $this->option('password') ?? password(label: 'New password', required: true);

        $validator = Validator::make(
            ['password' => $newPassword],
            ['password' => ['required', 'string', Password::defaults()]],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $user->update(['password' => $newPassword]);

        $this->info("Password updated for [{$user->email}].");

        return self::SUCCESS;
    }
}
