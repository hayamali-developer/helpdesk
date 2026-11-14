<?php    
namespace App\Services;


use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;
use DomainException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class  TicketService
{

public function __construct(
    protected TicketRepository $ticketRepository
){

}
 public function createTicket(array $data ,User $user)
 {

    $payload=[
        "title"=> $data["title"],
        "description"=> $data["description"],
        "status"=> $data["status"] ?? 'open',
        'priority'=> $data['priority'] ??'medium',
    ];

    return $this->ticketRepository->create($payload, $user);
 }


public function updateTicket(array $data, Ticket $ticket): Ticket
    {
        if (Arr::has($data, 'status')) {
            $this->updateStatus($ticket, $data['status']);
        }

        if (Arr::has($data, 'assignee_id')) {
            $this->updateAssignee($ticket, $data['assignee_id']);
        }

        $ticket->save();

        return $ticket->load(['user', 'assignee']);
    }

    private function updateStatus(Ticket $ticket, TicketStatus|string $status): void
    {
        $newStatus = $status instanceof TicketStatus
            ? $status
            : TicketStatus::from($status);

        $this->runDomainAction(
            fn () => $ticket->transitionTo($newStatus),
            'status'
        );
    }

    private function updateAssignee(Ticket $ticket, ?int $assigneeId): void
    {
        if (is_null($assigneeId)) {
            $ticket->unassign();
            return;
        }

        $assignee = User::select(['id', 'role'])->findOrFail($assigneeId);

        $this->runDomainAction(
            fn () => $ticket->assignTo($assignee),
            'assignee_id'
        );
    }

    private function runDomainAction(callable $action, string $field): void
    {
        try {
            $action();
        } catch (DomainException $e) {
            throw ValidationException::withMessages([$field => [$e->getMessage()]]);
        }
    }

}
