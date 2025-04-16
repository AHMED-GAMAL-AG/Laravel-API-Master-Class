<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

    /**
     * Checks if a specific relationship is included in the query parameter.
     *
     * This method retrieves the 'include' query parameter from the request,
     * splits it into an array of lowercase values, and checks if the specified
     * relationship exists within that array.
     *
     * @param string $relationship The name of the relationship to check.
     * @return bool Returns true if the relationship is included, false otherwise.
     */
    public function include(string $relationship)
    {
        $pram = request()->query('include');

        if (is_null($pram)) {
            return false;
        }

        $includeValues = explode(',', strtolower($pram));

        return in_array($relationship, $includeValues);
    }

    public function isAble($ability, $targetModel)
    {
        $gate = Gate::policy($targetModel::class, $this->policyClass,);
        return $gate->authorize($ability, $targetModel);
    }
}
