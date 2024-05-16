<?php

namespace App\Misc;

use App\Models\Character;

class CharacterCreation
{
    public static function dodge(Character $character): int
    {
        return floor($character->dexterity / 2);
    }

    public static function sanity(Character $character): int
    {
        return $character->power;
    }

    public static function magicPoints(Character $character): int
    {
        return floor($character->power / 5);
    }

    public static function hitPoints(Character $character): int
    {
        return floor(($character->constitution + $character->size) / 5);
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
        $strAndSiz = $character->strength + $character->size;
        switch ($strAndSiz) {
            case $strAndSiz < 64:
                return '-2';
            case $strAndSiz >= 65 && $strAndSiz < 84:
                return '-1';
            case $strAndSiz >= 85 && $strAndSiz < 124:
                return 'none';
            case $strAndSiz >= 125 && $strAndSiz < 164:
                return '+1D4';
            case $strAndSiz >= 165 && $strAndSiz < 204:
                return '+1D6';
        }

    }

    public static function build(Character $character): int
    {
        $strAndSiz = $character->strength + $character->size;
        switch ($strAndSiz) {
            case $strAndSiz < 64:
                return -2;
            case $strAndSiz >= 65 && $strAndSiz < 84:
                return -1;
            case $strAndSiz >= 85 && $strAndSiz < 124:
                return 0;
            case $strAndSiz >= 125 && $strAndSiz < 164:
                return +1;
            case $strAndSiz >= 165 && $strAndSiz < 204:
                return +2;
        }

    }
}
