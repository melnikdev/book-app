<?php

use App\Actions\Authentication\LoginUser;
use App\Actions\Book\BookSearch;
use App\Actions\Comment\CommentBookUser;
use App\Actions\Comment\VoteUser;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/search', BookSearch::class);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::post('/books/{id}/comments', CommentBookUser::class);

    Route::post('/comments/{id}/vote', VoteUser::class);
    Route::get('/auth/me', [AuthController::class, 'me'])->name('me');
});

Route::post('/auth/login', LoginUser::class)->name('login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');