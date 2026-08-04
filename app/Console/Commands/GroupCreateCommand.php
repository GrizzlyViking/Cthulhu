<?php

namespace App\Console\Commands;

use App\Enums\Era;
use App\Models\Group;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class GroupCreateCommand extends Command
{
    protected $signature = 'group:create {name? : The name of the new group} {--era= : The era the group plays in (1920s or modern)}';

    protected $description = 'Create a new group of investigators';

    public function handle(): int
    {
        $name = $this->argument('name') ?? text(label: 'Group name', required: true);

        if (Group::query()->where('name', $name)->exists()) {
            $this->error("A group named [{$name}] already exists.");

            return self::FAILURE;
        }

        $era = $this->resolveEra();

        if ($era === null) {
            return self::FAILURE;
        }

        $group = Group::create(['name' => $name, 'era' => $era]);

        $this->info("Group [{$group->name}] created (era: {$era->value}).");

        return self::SUCCESS;
    }

    private function resolveEra(): ?Era
    {
        $option = $this->option('era');

        if ($option !== null) {
            $era = Era::tryFrom(strtolower($option));

            if ($era === null) {
                $valid = implode(', ', Arr::map(Era::cases(), fn (Era $case): string => $case->value));
                $this->error("Invalid era [{$option}]. Valid eras are: {$valid}.");

                return null;
            }

            return $era;
        }

        $value = select(
            label: 'Era',
            options: Arr::mapWithKeys(
                Era::cases(),
                fn (Era $case): array => [$case->value => "{$case->label()} ({$case->value})"],
            ),
        );

        return Era::from($value);
    }
}
