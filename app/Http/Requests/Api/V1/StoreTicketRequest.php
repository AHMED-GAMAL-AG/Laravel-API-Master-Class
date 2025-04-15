<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TicketStatus;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.description' => ['required', 'string'],
            'data.attributes.title' => ['required', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'string', Rule::in(TicketStatus::toArray())],
        ];

        if ($this->routeIs('api.v1.tickets.store')) {
            $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }
}
