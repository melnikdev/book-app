<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('index method returns a paginated list of books for authenticated user', function () {
    Book::factory(50)->create();

    $response = $this->actingAs($this->user)
        ->getJson('/api/v1/books');

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(15)
        ->and($response->json('meta'))->toHaveKeys(['current_page', 'last_page', 'per_page', 'total']);
});

test('index method returns an empty list when there are no books for authenticated user', function () {
    $response = $this->actingAs($this->user)
        ->getJson('/api/v1/books');

    $response->assertOk();
    expect($response->json('data'))->toBeArray()->toBeEmpty()
        ->and($response->json('meta.total'))->toBe(0);
});

test('index method returns unauthorized error for unauthenticated user', function () {
    $response = $this->getJson('/api/v1/books');

    $response->assertUnauthorized();
});