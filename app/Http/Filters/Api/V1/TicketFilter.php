<?php

namespace App\Http\Filters\Api\V1;

use App\Enums\TicketStatus;

class TicketFilter extends QueryFilter
{
    public function status($value)
    {
        $value = explode(',', $value);
        $validStatuses = TicketStatus::toArray();

        $validValue = array_filter($value, function ($value) use ($validStatuses) {
            return in_array($value, $validStatuses);
        });

        if (!empty($validValue)) {
            $this->builder->whereIn('status', $validValue);
        }
    }

    public function title($value)
    {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function include($value)
    {
        // Check if the relationship exists on the model
        $model = $this->builder->getModel();

        if (method_exists($model, $value)) {
            $this->builder->with($value);
        }
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return  $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return  $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
