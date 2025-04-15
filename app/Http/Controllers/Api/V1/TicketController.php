<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ticket;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Models\User;
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
        try {
            $user = User::findOrFail($request->input('data.relationships.author.data.id'));
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
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
