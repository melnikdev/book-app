<?php

namespace Tests\Feature\Auth;

use App\Events\UserRegisteredEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tymon\JWTAuth\Facades\JWTAuth;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('register succeeds with valid data', function () {
    Event::fake();

    $user = User::factory()->make(['password' => 'password']);
    $password = $user->password;

    JWTAuth::shouldReceive('fromUser')
        ->once()
        ->with(Mockery::on(fn($createdUser) => $createdUser->email === $user->email))
        ->andReturn('mock_token');

    $response = postJson(route('register'), [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    Event::assertDispatched(UserRegisteredEvent::class);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token', 'token_type', 'expires_in',
        ]);

    expect(User::query()->where('email', $user->email)->exists())->toBeTrue();
});

test('register fails with missing required fields', function () {
    $response = postJson(route('register'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('register fails with invalid email', function () {
    $user = User::factory()->make();

    $response = postJson(route('register'), [
        'name' => $user->name,
        'email' => 'invalid_email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('register fails if email is already taken', function () {
    $existingUser = User::factory()->create();

    $response = postJson(route('register'), [
        'name' => $existingUser->name,
        'email' => $existingUser->email,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('register fails if passwords do not match', function () {
    $user = User::factory()->make();

    $response = postJson(route('register'), [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'password123',
        'password_confirmation' => 'password_not_matching',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});