<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketController extends Controller
{
    public function index(TicketFilter $filters, $authorId)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $authorId)->filter($filters)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
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
}
