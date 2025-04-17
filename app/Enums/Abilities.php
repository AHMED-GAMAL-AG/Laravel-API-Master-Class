<?php

namespace App\Enums;

use App\Models\User;

enum Abilities: string
{
    case VIEW_Ticket = 'ticket:view';
    case UPDATE_Ticket = 'ticket:update';
    case DELETE_Ticket = 'ticket:delete';
    case CREATE_Ticket = 'ticket:create';

    case DELETE_OWN_TICKET = 'ticket:delete:own';
    case UPDATE_OWN_TICKEtT = 'ticket:update:own';

    case VIEW_USER = 'user:view';
    case UPDATE_USER = 'user:update';
    case CREATE_USER = 'user:create';
    case DELETE_USER = 'user:delete';

    public static function getAbilities(User $user): array
    {
        $abilities = [];
        if ($user->is_manger) {
            $abilities = array_merge($abilities, [
                self::VIEW_USER,
                self::UPDATE_USER,
                self::CREATE_USER,
                self::DELETE_USER,
                self::VIEW_Ticket,
                self::UPDATE_Ticket,
                self::DELETE_Ticket,
                self::CREATE_Ticket,

            ]);
        } else {
            $abilities = array_merge($abilities, [
                self::UPDATE_OWN_TICKEtT,
                self::UPDATE_OWN_TICKEtT,
                self::DELETE_OWN_TICKET,
            ]);
        }

        return $abilities;
    }
}
