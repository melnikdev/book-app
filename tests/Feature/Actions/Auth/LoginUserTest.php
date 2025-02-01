<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Authentication\LoginUser;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

it('logs in user with valid credentials', function () {
    $mockedToken = 'test_token';
    $mockedTTL = 60;

    Auth::shouldReceive('attempt')
        ->once()
        ->with(['email' => 'user@example.com', 'password' => 'password123'])
        ->andReturn($mockedToken);

    Auth::shouldReceive('factory->getTTL')
        ->andReturn($mockedTTL);

    $result = LoginUser::run(['email' => 'user@example.com', 'password' => 'password123']);

    expect($result)->toBe([
        'data' => [
            'accessToken' => $mockedToken,
            'tokenType' => 'bearer',
            'expiresIn' => $mockedTTL * 60,
        ],
        'status' => 200
    ]);
});

it('Unauthenticated for invalid credentials', function () {
    Auth::shouldReceive('attempt')
        ->once()
        ->with(['email' => 'user@example.com', 'password' => 'wrong_password'])
        ->andReturn(false);

    $result = LoginUser::run(['email' => 'user@example.com', 'password' => 'wrong_password']);

    expect($result)->toBe([
        'data' => ['error' => 'Unauthenticated.'],
        'status' => 401
    ]);
});