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

    public function values(): array
    {
        return Arr::map(self::cases(), fn (self $role) => $role->value);
    }

    public function labels(): array
    {
        return Arr::map(self::cases(), fn (self $role) => $role->label());
    }
}
