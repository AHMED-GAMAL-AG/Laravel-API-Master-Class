<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ticket;
use App\Policies\Api\V1\TicketPolicy;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

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
        try {
            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes()));
        } catch (AuthorizationException $exception) {
            return $this->errorResponse('Unauthorized', [
                'error' => 'You do not have permission to create a ticket.',
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ticketId, TicketFilter $filters)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            $this->isAble('view', $ticket);

            return TicketResource::collection($ticket->filter($filters)->paginate());
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', [
                'error' => 'The provided ticket ID does not exist.',
            ], 404);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse('Unauthorized', [
                'error' => 'You do not have permission to view this ticket.',
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Ticket $ticket, UpdateTicketRequest $request)
    {
        $this->isAble('update', $ticket);
        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
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
