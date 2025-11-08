<?php

namespace App\Enums;

interface EnumInterface
{
    public function label(): string;
    public function labels(): array;
    public function values(): array;
}
