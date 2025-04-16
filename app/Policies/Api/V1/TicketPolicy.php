<?php

namespace App\Policies\Api\V1;

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

    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }
}
