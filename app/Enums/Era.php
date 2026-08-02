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
}
