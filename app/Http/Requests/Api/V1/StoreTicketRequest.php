<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
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

        if ($this->routeIs('api.v1.authors.store')) {
            $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'data.attributes.title.required' => 'The title field is required.',
            'data.attributes.status.required' => 'The status field is required.',
            'data.relationships.author.data.id.required' => 'The author field is required.',
            'data.attributes.description.required' => 'The description field is required.',
            'data.attributes.status.in' => 'The selected status is invalid. Valid values are: ' . implode(', ', TicketStatus::toArray()),
        ];
    }
}
