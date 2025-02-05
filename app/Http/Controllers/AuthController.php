<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

class AuthController
{
    use ApiResponses;

    #[OA\Post(
        path: '/api/v1/auth/register',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', description: 'Name of the user', type: 'string',
                        example: 'John Doe'),
                    new OA\Property(property: 'email', description: 'Email of the user', type: 'string',
                        example: 'johndoe@example.com'),
                    new OA\Property(property: 'password', description: 'Password for the user account', type: 'string',
                        example: 'password123'),
                    new OA\Property(property: 'password_confirmation', description: 'Password confirmation',
                        type: 'string', example: 'password123')
                ]
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful registration',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', description: 'JWT access token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600)
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', description: 'Error message', type: 'string')
                    ]
                )
            )
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->safe();

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->successResponse(['message' => 'Successfully logged out']);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(auth()->user());
    }

    public function refresh(Request $request): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return $this->successResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}