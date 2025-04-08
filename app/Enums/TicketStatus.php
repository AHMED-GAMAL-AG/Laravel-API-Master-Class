<?php

namespace App\Enums;


enum TicketStatus: string
{
    case HOLD = 'hold';
    case ACTIVE = 'active';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';

    public static function toArray()
    {
        return array_column(self::cases(), 'value');
    }
}
