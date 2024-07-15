<?php

namespace App\Enums;

enum EventType: string
{
    case default = 'default';
    case suggestion = 'suggestion';
    case vote = 'vote';
}
