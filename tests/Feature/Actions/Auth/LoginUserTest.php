<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Authentication\LoginUser;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

it('logs in user with valid credentials', function () {
    $credentials = ['email' => 'user@example.com', 'password' => 'password123'];
    $mockedToken = 'test_token';
    $mockedTTL = 60;

    Auth::shouldReceive('attempt')
        ->once()
        ->with($credentials)
        ->andReturn($mockedToken);

    Auth::shouldReceive('factory->getTTL')
        ->andReturn($mockedTTL);

    $result = LoginUser::run($credentials);

    expect($result)->toBe([
        'accessToken' => $mockedToken,
        'tokenType' => 'bearer',
        'expiresIn' => $mockedTTL * 60,
    ]);
});

it('Unauthenticated for invalid credentials', function () {
    $credentials = ['email' => 'user@example.com', 'password' => 'wrong_password'];
    $exception = null;

    Auth::shouldReceive('attempt')
        ->once()
        ->with($credentials)
        ->andReturn(false);

    try {
        app(LoginUser::class)->handle($credentials);
    } catch (\Exception $e) {
        $exception = $e;
    }

    expect($exception)->toBeInstanceOf(\Exception::class)
        ->and($exception->getMessage())->toBe('Unauthenticated.')
        ->and($exception->getCode())->toBe(Response::HTTP_UNAUTHORIZED);
});