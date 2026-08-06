<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum RoleEnum: string implements EnumInterface
{
    case PLAYER = 'player';
    case KEEPER = 'keeper';
    case ADMIN  = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::PLAYER => 'Player',
            self::KEEPER => 'Keeper of Arcane Lore',
            self::ADMIN  => 'Admin',
        };
    }

    public static function values(): array
    {
        return Arr::map(self::cases(), fn (self $role) => $role->value);
    }

    public function labels(): array
    {
        return Arr::map(self::cases(), fn (self $role) => $role->label());
    }

    /**
     * Every role as a value/label pair, for the admin role pickers.
     *
     * @return array<int, array{value: string, label: string, description: string}>
     */
    public static function options(): array
    {
        return Arr::map(self::cases(), fn (self $role): array => [
            'value'       => $role->value,
            'label'       => $role->label(),
            'description' => $role->description(),
        ]);
    }

    public function description(): string
    {
        return match ($this) {
            self::PLAYER => 'Owns and edits their own investigators.',
            self::KEEPER => 'Runs the game: sees and edits every sheet in the group, sends messages and rolls in secret.',
            self::ADMIN  => 'Manages the group itself, its members and their roles.',
        };
    }
}
