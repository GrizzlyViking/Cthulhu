<?php

namespace App\Enums;

enum Era: string
{
    case Twenties = '1920s';
    case Modern   = 'modern';

    public function label(): string
    {
        return match ($this) {
            self::Twenties => 'The Roaring Twenties',
            self::Modern   => 'Modern Day',
        };
    }

    /**
     * The short form, for a chip on a sheet where the long label would not fit.
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::Twenties => '1920s',
            self::Modern   => 'Modern',
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
     * Every era. What something available throughout is stored as, and what
     * anything new defaults to until somebody says otherwise.
     *
     * @return list<string>
     */
    public static function all(): array
    {
        return self::values();
    }

    /**
     * The eras as the frontend wants them: a value, a full label and a short one.
     *
     * @return list<array{value: string, label: string, short: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $era): array => [
                'value' => $era->value,
                'label' => $era->label(),
                'short' => $era->shortLabel(),
            ],
            self::cases(),
        );
    }
}
