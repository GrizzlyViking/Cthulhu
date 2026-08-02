<?php

namespace App\Misc;

use App\Models\Character;

class CharacterCreation
{
    public static function dodge(Character $character): int
    {
        return intdiv($character->dexterity, 2);
    }

    public static function sanity(Character $character): int
    {
        return $character->power;
    }

    public static function magicPoints(Character $character): int
    {
        return intdiv($character->power, 5);
    }

    public static function hitPoints(Character $character): int
    {
        return intdiv($character->constitution + $character->size, 10);
    }

    public static function moveRate(Character $character): int
    {
        if ($character->strength < $character->size && $character->dexterity < $character->size) {
            return 7;
        }

        if ($character->strength > $character->size && $character->dexterity > $character->size) {
            return 9;
        }

        return 8;
    }

    public static function damageBonus(Character $character): string
    {
        return match (true) {
            $character->strength + $character->size <= 64  => '-2',
            $character->strength + $character->size <= 84  => '-1',
            $character->strength + $character->size <= 124 => 'none',
            $character->strength + $character->size <= 164 => '+1D4',
            $character->strength + $character->size <= 204 => '+1D6',
            default                                        => '+2D6',
        };
    }

    public static function build(Character $character): int
    {
        return match (true) {
            $character->strength + $character->size <= 64  => -2,
            $character->strength + $character->size <= 84  => -1,
            $character->strength + $character->size <= 124 => 0,
            $character->strength + $character->size <= 164 => 1,
            $character->strength + $character->size <= 204 => 2,
            default                                        => 3,
        };
    }

    public static function half(int $value): int
    {
        return intdiv($value, 2);
    }

    public static function fifth(int $value): int
    {
        return intdiv($value, 5);
    }
}
