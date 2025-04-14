<?php

namespace App\Http\Filters\Api\V1;

class AuthorFilter extends QueryFilter
{
    protected $sortable = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at',
    ];

    public function id($value)
    {
        $this->builder->whereIn('id', explode(',', $value));
    }

    public function email($value)
    {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function name($value)
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
