<?php

namespace App\Enums;

enum Characteristic: string
{
    case STR = 'strength';
    case DEX = 'dexterity';
    case INT = 'intelligence';
    case CON = 'constitution';
    case APP = 'appearance';
    case POW = 'power';
    case SIZ = 'size';
    case EDU = 'education';
    case MOVE_RATE = 'move rate';

    public function order(): int
    {
        return match ($this) {
            self::STR => 1,
            self::DEX => 2,
            self::INT => 3,
            self::CON => 4,
            self::APP => 5,
            self::POW => 6,
            self::SIZ => 7,
            self::EDU => 8,
            self::MOVE_RATE => 9
        };
    }

    public function startingValue(): string
    {
        return match ($this) {
            self::STR, self::CON, self::APP, self::DEX, self::POW => '(3d6)x5',
            self::SIZ, self::EDU, self::INT => '(2d6+6)*5',
        };
    }
}
