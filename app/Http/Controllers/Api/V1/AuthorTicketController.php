<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketController extends ApiController
{
    /**
     * Get all tickets
     *
     * Retrieves all tickets created by a specific user.
     *
     * @group Managing Tickets by Author
     *
     * @urlParam author_id integer required The author's ID. No-example
     *
     * @response 200 {"data":[{"type":"user","id":3,"attributes":{"name":"Mr. Henri Beatty MD","email":"bmertz@example.net","isManager":false,"email_verified_at":"2024-03-14T04:41:51.000000Z","created_at":"2024-03-14T04:41:51.000000Z","updated_at":"2024-03-14T04:41:51.000000Z"},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/authors\/3"}}],"links":{"first":"http:\/\/localhost:8000\/api\/v1\/authors?page=1","last":"http:\/\/localhost:8000\/api\/v1\/authors?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":1,"last_page":1,"links":[{"url":null,"label":"&laquo; Previous","active":false},{"url":"http:\/\/localhost:8000\/api\/v1\/authors?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false}],"path":"http:\/\/localhost:8000\/api\/v1\/authors","per_page":15,"to":1,"total":10}}
     *
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=name
     * @queryParam filter[name] Filter by name. Wildcards are supported.
     * @queryParam filter[email] Filter by email. Wildcards are supported.
     */
    public function index(TicketFilter $filters, $authorId)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $authorId)->filter($filters)->paginate()
        );
    }

    /**
     * Create a ticket
     *
     * Creates a ticket for the specific user.
     *
     * @group Managing Tickets by Author
     *
     * @urlParam author_id integer required The author's ID. No-example
     *
     */
    public function store(StoreTicketRequest $request, $authorId)
    {
        try {
            $user = User::findOrFail($authorId);
        } catch (ModelNotFoundException $exception) {
            $this->successResponse('User not found', [
                'error' => 'The provided user ID does not exist.',
            ]);
        }

        $attributes = [
            'user_id' => $user->id,
            'title' => $request->input('data.attributes.title'),
            'status' => $request->input('data.attributes.status'),
            'description' => $request->input('data.attributes.description'),
        ];

        return new TicketResource(Ticket::create($attributes));
    }

    /**
     * Delete an author's ticket
     *
     * Deletes an author's ticket.
     *
     * @group Managing Tickets by Author
     * @urlParam author_id integer required The author's ID. No-example
     * @urlParam id integer required The ticket ID. No-example
     * @response {}
     */
    public function destroy($authorId, $ticketId)
    {
        try {
            $ticket = Ticket::where('user_id', $authorId)->findOrFail($ticketId);
            $ticket->delete();

            return $this->successResponse('Ticket deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', [
                'error' => 'The provided ticket ID does not exist.',
            ], 404);
        }
    }
}
