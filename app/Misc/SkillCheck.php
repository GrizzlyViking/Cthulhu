<?php

namespace App\Misc;

/**
 * A percentile roll against a value on a sheet.
 *
 * The ladder is the one this table already plays with, kept verbatim from the
 * Keeper's first secret roll: 1 is a critical, 99 and 100 are fumbles, and a
 * fifth and a half of the value are the extreme and hard thresholds. It is a
 * house simplification of the Keeper Rulebook's fumble rule, and changing it
 * would change the game — so it lives here rather than being written out
 * wherever a roll is needed.
 */
final class SkillCheck
{
    /**
     * Roll against a value and say how it went.
     *
     * @return array{roll: int, value: int, outcome: string, success: bool}
     */
    public static function against(int $value): array
    {
        $roll = random_int(1, 100);

        $outcome = match (true) {
            $roll >= 99                     => 'Critical Failure',
            $roll === 1                     => 'Critical Success',
            $roll <= (int) ceil($value / 5) => 'Extreme Success',
            $roll <= (int) ceil($value / 2) => 'Hard Success',
            $roll <= $value                 => 'Success',
            default                         => 'Failure',
        };

        return [
            'roll'    => $roll,
            'value'   => $value,
            'outcome' => $outcome,
            // Mirrors the ladder above rather than re-deriving it: a 1 is a
            // success however hopeless the skill, and a 99 never is.
            'success' => $roll === 1 || ($roll < 99 && $roll <= $value),
        ];
    }
}
