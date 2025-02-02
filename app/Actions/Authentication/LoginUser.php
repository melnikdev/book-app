<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class LoginUser
{
    use AsAction, ApiResponses;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function handle(array $credentials): array
    {
        if (! $token = auth()->attempt($credentials)) {
            throw new \Exception('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
        }

        return [
            'accessToken' => $token,
            'tokenType' => 'bearer',
            'expiresIn' => auth()->factory()->getTTL() * 60
        ];
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $data = $request->only('email', 'password');

        try {
            $response = $this->handle($data);
        } catch (\Exception $e) {
            return $this->errorResponse([], $e->getMessage(), $e->getstatusCode());
        }

        return $this->successResponse($response);
    }

}
