<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class LoginUser
{
    use AsAction;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function handle(array $credentials): array
    {
        if (! $token = auth()->attempt($credentials)) {
            return ['data' => ['error' => 'Unauthenticated.'], 'status' => 401];
        }

        return [
            'data' => [
                'accessToken' => $token,
                'tokenType' => 'bearer',
                'expiresIn' => auth()->factory()->getTTL() * 60
            ],
            'status' => 200
        ];
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $data = $request->only('email', 'password');
        $response = $this->handle($data);

        return response()->json($response['data'], $response['status']);
    }
}
