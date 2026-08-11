<?php

namespace App\Misc;

use InvalidArgumentException;

class Roll
{
    /**
     * Roll a dice expression: "3d6", "2d6+6", "(3d6)*5", "(2d6+6)*5".
     *
     * Every part is read afresh on each call. The addition and the multiplier
     * used to live in static properties that were only ever written when the
     * expression carried them, so they leaked into the next roll: a "(3d6)*5"
     * following a "(2d6+6)*5" silently added the previous six and handed back up
     * to 120 where 90 is the maximum — which is exactly how a conjured
     * characteristic came out at 95.
     */
    public static function dice(string $dice): int
    {
        if (preg_match('/(\d+)[dD](\d+)/', $dice, $match) !== 1) {
            throw new InvalidArgumentException("“{$dice}” is not a dice expression.");
        }

        [, $rolls, $eyes] = $match;

        $added      = preg_match('/\+(\d+)/', $dice, $addition) === 1 ? (int) $addition[1] : 0;
        $multiplier = preg_match('/[*x](\d+)/', $dice, $multi) === 1 ? (int) $multi[1] : 1;

        $sum = 0;

        for ($i = 0; $i < (int) $rolls; $i++) {
            $sum += rand(1, (int) $eyes);
        }

        return ($sum + $added) * $multiplier;
    }
}
