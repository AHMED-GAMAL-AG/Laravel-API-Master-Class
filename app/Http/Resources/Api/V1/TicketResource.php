<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    // public static $wrap = 'ticket';

    /**
     * Transform the resource into an array.
     * following the JSON API spec https://jsonapi.org
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'ticket',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->when(
                    $request->routeIs('api.v1.tickets.show'),
                    $this->description
                ),
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'users',
                        'id' => $this->user_id,
                    ],
                    'links' => [
                        'related' => route('api.v1.users.show', $this->user_id),
                    ],
                ],
            ],
            'includes' => new UserResource($this->whenLoaded('author')),
            'links' => [
                'self' => route('api.v1.tickets.show', $this->id),
            ],
        ];
    }
}
