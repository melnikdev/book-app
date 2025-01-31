<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('show returns book details for a valid ID', function () {
    $book = Book::factory()->create();

    $response = $this->actingAs($this->user)
        ->getJson(route('books.show', ['id' => $book->id]));

    $response
        ->assertStatus(200)
        ->assertJsonPath('data.id', $book->id)
        ->assertJsonPath('data.title', $book->title);
});

test('show returns 404 for an invalid ID', function () {
    $invalidId = 9999;

    $response = $this->actingAs($this->user)
        ->getJson(route('books.show', ['id' => $invalidId]));

    $response->assertStatus(404);
});

test('show method returns unauthorized error for unauthenticated user', function () {
    $response = $this->getJson(route('books.show', ['id' => 1]));

    $response->assertUnauthorized();
});