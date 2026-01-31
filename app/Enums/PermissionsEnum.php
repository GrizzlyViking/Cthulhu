<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum PermissionsEnum: string implements EnumInterface
{
    case EDIT_ANY_CHARACTER   = 'edit any character';
    case EDIT_OWN_CHARACTER   = 'edit own characters';
    case DELETE_OWN_CHARACTER = 'delete own character';
    case DELETE_ANY_CHARACTER = 'delete any character';
    case VIEW_ANY_CHARACTER   = 'view any character';
    case VIEW_OWN_CHARACTER   = 'view own characters';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function labels(): array
    {
        return Arr::map(self::cases(), fn (self $permission) => $permission->label());
    }

    public static function values(): array
    {
        return Arr::map(self::cases(), fn (self $permission) => $permission->value);
    }
}
