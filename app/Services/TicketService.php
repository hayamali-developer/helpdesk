<?php    
namespace App\Services;


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

}
