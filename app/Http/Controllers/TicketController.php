<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $ticket=$request->user()->tickets()->create([
           'title' => $request->validated('title'),
        'description' => $request->validated('description'),
        'priority' => $request->validated('priority', 'medium'),
        ]);

        return (new TicketResource($ticket))->response()
        ->withStatusCode(Response::HTTP_CREATED);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
