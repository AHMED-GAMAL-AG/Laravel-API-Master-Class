<?php

namespace App\Http\Controllers;

use App\Http\Requests\apiLoginRequest;
use App\Traits\apiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use apiResponses;

    public function login(apiLoginRequest $request)
    {
        return $this->success($request->get('email'));
    }
}
