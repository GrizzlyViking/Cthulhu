<?php

namespace App\Enums;

enum NotesVisibility: string
{
    case Everyone = 'everyone';
    case Keeper   = 'keeper';
    case Private  = 'private';
}
