<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(protected TicketService $ticketService) {}

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->createTicket(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Ticket saved successfully.',
            'ticket' => TicketResource::make($ticket),
        ], 201);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): JsonResponse
    {
        // Check state transition if status is provided in the request
        if ($request->has('status')) {
            $newStatus = $request->status();

            if (! $ticket->canTransitionTo($newStatus)) {
                return response()->json(['message' => 'Invalid status transition.'], 422);
            }
        }

        $ticket = $this->ticketService->updateTicket($request->validated(), $ticket);

        return response()->json([
            'message' => 'Ticket updated successfully.',
            'ticket' => TicketResource::make($ticket),
        ]);
    }
}
