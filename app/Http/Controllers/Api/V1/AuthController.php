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

    /**
     * Login
     *
     * Authenticates the user and returns the user's API token.
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {
     *     "data": {
     *         "token": "{YOUR_AUTH_KEY}"
     *     },
     *     "message": "Authenticated",
     *     "status": 200
     * }
     */
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

    /**
     * Register
     *
     * Creates a new user account.
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {
     *     "data": {
     *         "user": {
     *             "id": 1,
     *             "name": "John Doe",
     *             "email": "john@example.com",
     *             "created_at": "2023-01-01T00:00:00.000000Z",
     *             "updated_at": "2023-01-01T00:00:00.000000Z"
     *         }
     *     },
     *     "message": "User registered successfully",
     *     "status": 200
     * }
     */
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

    /**
     * Logout
     *
     * Signs out the user and destroy's the API token.
     *
     * @group Authentication
     * @response 200 {}
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Successfully logged out');
    }
}
