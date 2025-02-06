<?php

use App\Actions\Authentication\LoginUser;
use App\Actions\Book\BookElasticSearch;
use App\Actions\Book\BookSearch;
use App\Actions\Comment\CommentBookUser;
use App\Actions\Comment\VoteUser;
use App\Actions\Stream\StreamText;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/search', BookSearch::class)->name('books.search');
    Route::get('/books/elasticsearch', BookElasticSearch::class)->name('books.elasticsearch');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
    Route::post('/books/{id}/comments', CommentBookUser::class)->name('books.comments.store');

    Route::post('/comments/{id}/vote', VoteUser::class);
    Route::get('/auth/me', [AuthController::class, 'me'])->name('me');
});

Route::post('/auth/login', LoginUser::class)->name('login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('stream', StreamText::class)->name('stream');