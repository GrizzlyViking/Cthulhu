<?php

namespace App\Enums;

/**
 * Where the money for a purchase came from.
 *
 * `Nothing` is not an absence of choice — it is the player saying this one cost
 * them nothing: found on the ground, taken off a corpse, a gift from an uncle.
 * The sheet trusts them, so it is offered alongside the two purses rather than
 * hidden behind a zero price.
 */
enum Purse: string
{
    case Cash    = 'cash';
    case Assets  = 'assets';
    case Nothing = 'nothing';

    public function label(): string
    {
        return match ($this) {
            self::Cash    => 'Cash',
            self::Assets  => 'Assets',
            self::Nothing => 'Nothing — it cost me nothing',
        };
    }

    /**
     * The column on `characters` this purse is kept in, or null when nothing is
     * to be deducted at all.
     */
    public function column(): ?string
    {
        return match ($this) {
            self::Cash    => 'cash',
            self::Assets  => 'assets',
            self::Nothing => null,
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * The purses as the sheet wants them: a value and a label.
     *
     * @return list<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $purse): array => ['value' => $purse->value, 'label' => $purse->label()],
            self::cases(),
        );
    }
}
