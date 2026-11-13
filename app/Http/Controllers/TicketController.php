<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class TicketController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
       protected TicketService $ticketService
    ){}
   
    public function index(Request $request)
    {
        $this->authorize("viewAny", Ticket::class);
        $query=Ticket::query()
        ->forUser($request->user())
        ->withStatus($request->input("status"))
        ->withPriority($request->input("priority"))
        ->latest()
        ->paginate(10);

        return TicketResource::collection($query);
        
    }
    
    /**
     * Show the form for creating a new resource.
     */
      public function create(){}

    /**
     * Store a newly created resource in storage.
     */
         public function store(StoreTicketRequest $request)
    {
        $this->authorize("create", Ticket::class);
        $ticket = $this->ticketService->createTicket($request->validated(), 
        $request->user());

    return (new TicketResource($ticket))
        ->response()
        ->setStatusCode(Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request,Ticket $ticket)
    {
      $this->authorize("update", $ticket);
      $updateTicket=$this->ticketService->updateTicket($ticket ,$request->validated());
      return (new TicketResource($updateTicket))->response()
      ->setStatusCode(Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
