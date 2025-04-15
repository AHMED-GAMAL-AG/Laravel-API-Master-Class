<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes()
    {
        $attributeMap = [
            'data.attributes.title' => 'title',
            'data.attributes.status' => 'status',
            'data.attributes.description' => 'description',
            'data.relationships.author.data.id' => 'user_id',
        ];

        $attributeToUpdateOrCreate = [];

        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributeToUpdateOrCreate[$attribute] = $this->input($key);
            }
        }

        return $attributeToUpdateOrCreate;
    }

    public function messages()
    {
        return [
            'data.attributes.status.in' => 'The selected status is invalid. Valid values are: ' . implode(', ', TicketStatus::toArray()),
        ];
    }
}
