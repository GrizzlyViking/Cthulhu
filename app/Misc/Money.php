<?php

namespace App\Misc;

use App\Enums\Era;

/**
 * Reading the handbook's price cells, and writing money back out.
 *
 * The catalogues keep their prices exactly as the book prints them, which means
 * a column of free text rather than a column of numbers: "$18.50", "5¢-20¢",
 * "9¢/lb.", and — for weapons — "$7/$75", the 1920s price and the modern one
 * side by side. Nothing here corrects the book; it only works out what to put in
 * front of a player about to spend, and the player is free to type over it.
 */
class Money
{
    /**
     * What a price cell suggests something costs, or null when the book gives
     * no figure ("-", a blank, or a cell with no number in it at all).
     *
     * Passing an era treats a "a/b" cell as the weapons table's 1920s/modern
     * pair. The equipment catalogue is one price per row and uses the slash for
     * units instead ("9¢/lb."), so it asks without one.
     */
    public static function fromCostCell(?string $cost, ?Era $era = null): ?float
    {
        $cell = trim((string) $cost);

        if ($cell === '' || $cell === '-') {
            return null;
        }

        if ($era !== null && str_contains($cell, '/')) {
            [$twenties, $modern] = array_pad(explode('/', $cell, 2), 2, '');

            $cell = $era === Era::Modern ? $modern : $twenties;
        }

        return self::firstFigure($cell);
    }

    /**
     * The first amount named in a cell — the cheap end of a range, which is the
     * kinder figure to put in front of a player who is about to haggle anyway.
     */
    private static function firstFigure(string $cell): ?float
    {
        $cell = trim($cell);

        if ($cell === '' || $cell === '-') {
            return null;
        }

        // Cents first: "5¢-20¢" would otherwise read as five dollars.
        if (preg_match('/(\d[\d,]*(?:\.\d+)?)\s*¢/u', $cell, $matches) === 1) {
            return self::toFloat($matches[1]) / 100;
        }

        if (preg_match('/(\d[\d,]*(?:\.\d+)?)/u', $cell, $matches) === 1) {
            return self::toFloat($matches[1]);
        }

        return null;
    }

    private static function toFloat(string $figure): float
    {
        return (float) str_replace(',', '', $figure);
    }

    /**
     * Money as the sheet prints it: cents only when there are cents, so a
     * revolver costs $25 rather than $25.00 and a nickel still costs $0.05.
     */
    public static function format(float|int|null $amount): string
    {
        $amount = (float) ($amount ?? 0);
        $sign   = $amount < 0 ? '-' : '';

        return $sign.'$'.number_format(abs($amount), fmod(abs($amount), 1) === 0.0 ? 0 : 2);
    }
}
