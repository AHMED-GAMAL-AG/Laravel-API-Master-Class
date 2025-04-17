<?php

namespace App\Policies\Api\V1;

use App\Enums\Abilities;
use App\Models\User;
use App\Models\Ticket;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CREATE_Ticket->value);
    }

    public function update(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::UPDATE_Ticket->value)) {
            return true;
        } elseif ($user->tokenCan(Abilities::UPDATE_OWN_TICKEtT->value)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }

    public function view(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::VIEW_Ticket->value)) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Ticket $ticket)
    {
        if ($user->tokenCan(Abilities::DELETE_Ticket->value)) {
            return true;
        } elseif ($user->tokenCan(Abilities::DELETE_OWN_TICKET->value)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }
}
