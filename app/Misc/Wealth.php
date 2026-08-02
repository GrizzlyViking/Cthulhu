<?php

namespace App\Misc;

use App\Enums\Era;

/**
 * Table II: Cash and Assets (Investigator Handbook, p.57).
 */
class Wealth
{
    /**
     * @return array{living_standard: string, cash: float, assets: float, spending_level: float, description: string}
     */
    public static function for(int $creditRating, Era $era): array
    {
        $bracket = self::bracket($creditRating);

        [$cash, $assets, $spendingLevel] = match ($era) {
            Era::Twenties => match ($bracket) {
                'Penniless'  => [0.5, 0, 0.5],
                'Poor'       => [$creditRating * 1, $creditRating * 10, 2],
                'Average'    => [$creditRating * 2, $creditRating * 50, 10],
                'Wealthy'    => [$creditRating * 5, $creditRating * 500, 50],
                'Rich'       => [$creditRating * 20, $creditRating * 2000, 250],
                'Super Rich' => [50000, 5000000, 5000],
            },
            Era::Modern => match ($bracket) {
                'Penniless'  => [10, 0, 10],
                'Poor'       => [$creditRating * 20, $creditRating * 200, 40],
                'Average'    => [$creditRating * 40, $creditRating * 1000, 200],
                'Wealthy'    => [$creditRating * 100, $creditRating * 10000, 1000],
                'Rich'       => [$creditRating * 400, $creditRating * 40000, 5000],
                'Super Rich' => [1000000, 100000000, 100000],
            },
        };

        return [
            'living_standard' => $bracket,
            'cash'            => (float) $cash,
            'assets'          => (float) $assets,
            'spending_level'  => (float) $spendingLevel,
            'description'     => self::description($bracket),
        ];
    }

    private static function bracket(int $creditRating): string
    {
        return match (true) {
            $creditRating < 1  => 'Penniless',
            $creditRating < 10 => 'Poor',
            $creditRating < 50 => 'Average',
            $creditRating < 90 => 'Wealthy',
            $creditRating < 99 => 'Rich',
            default            => 'Super Rich',
        };
    }

    private static function description(string $bracket): string
    {
        return match ($bracket) {
            'Penniless'  => 'Living on the street. Travel by walking, hitchhiking, or stowing away.',
            'Poor'       => 'A roof overhead and at least one meager meal a day. The cheapest rental housing and public transport.',
            'Average'    => 'A reasonable level of comfort: an average home or apartment, three meals a day, standard travel.',
            'Wealthy'    => 'Luxury and comfort: a substantial residence, perhaps domestic help, first class travel, expensive hotels.',
            'Rich'       => 'Great luxury: a plush residence or estate with abundant domestic help, second homes, top hotels.',
            'Super Rich' => 'Money is no object. Among the richest people in the world.',
        };
    }
}
