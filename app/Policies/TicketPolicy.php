<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id
            || ($user->role?->canManageTickets() ?? false);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->role?->canManageTickets() ?? false;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}
