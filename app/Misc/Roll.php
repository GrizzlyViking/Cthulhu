<?php

namespace App\Misc;

class Roll
{
    protected static int $rolls = 1;

    protected static int $eyes = 6;

    protected static int $added = 0;

    protected static int $multiplier;

    public static function dice($dice): int
    {
        preg_match('/(\d+)[dD](\d+)/', $dice, $match);

        [$discard, self::$rolls, self::$eyes] = $match;

        if (preg_match('/\+(\d+)/', $dice, $addition)) {
            self::$added = $addition[1];
        }

        if (preg_match('/[*x](\d+)/', $dice, $multi)) {
            self::$multiplier = $multi[1];
        }

        $sum_of_eyes = 0;

        for ($i = 0; $i < self::$rolls; $i++) {
            $sum_of_eyes += rand(1, self::$eyes);
        }

        return ($sum_of_eyes + self::$added) * self::$multiplier;
    }
}
