<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show($ticketId, TicketFilter $filters)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            return TicketResource::collection($ticket->filter($filters)->paginate());
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', [
                'error' => 'The provided ticket ID does not exist.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request,  $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', [
                'error' => 'The provided ticket ID does not exist.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            $ticket->delete();

            return $this->successResponse('Ticket deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', [
                'error' => 'The provided ticket ID does not exist.',
            ], 404);
        }
    }
}
