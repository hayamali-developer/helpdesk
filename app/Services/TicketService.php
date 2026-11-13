<?php    
namespace App\Services;


use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\TicketRepository;

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


 public function updateTicket(array $data ,Ticket $ticket){

   if(isset($data['status']) && is_string($data['status'])){
    $data['status']= TicketStatus::tryFrom($data['status']);
   }
    
      $ticket->update($data);
      return $ticket->load(["user","assignee"]);

 }

}
