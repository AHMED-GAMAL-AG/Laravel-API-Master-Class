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
     * Get all tickets
     *
     * @group Managing Tickets
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=title,-created_at
     * @queryParam filter[status] Filter by status code: active, canceled, hold, completed. No-example
     * @queryParam filter[title] Filter by title. Wildcards are supported. Example: *fix*
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Create a ticket
     *
     * Creates a new ticket record. Users can only create tickets for themselves. Managers can create tickets for any user.
     *
     * @group Managing Tickets
     *
     * @response {"data":{"type":"ticket","id":107,"attributes":{"title":"new ticket","description":"test ticket","status":"accepted","created_at":"2024-03-26T04:40:48.000000Z","updatedAt":"2024-03-26T04:40:48.000000Z"},"relationships":{"author":{"data":{"type":"user","id":1},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/1"}}},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/tickets\/107"}}}
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
     * Show a specific ticket.
     *
     * Display an individual ticket.
     *
     * @group Managing Tickets
     *
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
     * Update Ticket
     *
     * Update the specified ticket in storage.
     *
     * @group Managing Tickets
     *
     */
    public function update(Ticket $ticket, UpdateTicketRequest $request)
    {
        $this->isAble('update', $ticket);
        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    /**
     * Delete ticket.
     *
     * Remove the specified resource from storage.
     *
     * @group Managing Tickets
     *
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
