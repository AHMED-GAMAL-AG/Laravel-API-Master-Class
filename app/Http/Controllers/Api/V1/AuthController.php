<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Abilities;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\V1\ApiAuthRequest;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(ApiAuthRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $user = User::where('email', $credentials['email'])->first();

        return $this->successResponse(
            'Authenticated successfully',
            [
                'user' => $user,
                'token' => $user->createToken(
                    name: 'auth_token',
                    expiresAt: now()->addDays(7),
                    abilities: Abilities::getAbilities($user),
                )->plainTextToken,
            ]
        );
    }

    public function register(ApiAuthRequest $request)
    {
        $credentials = $request->validated();

        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
        ]);

        return $this->successResponse(
            'User registered successfully',
            [
                'user' => $user,
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Successfully logged out');
    }
}
