<?php

use App\Actions\Authentication\LoginUser;
use App\Actions\Comment\VoteUser;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    Route::get('/book/{id}', [BookController::class, 'show']);
    Route::post('/book/{id}/comments', [CommentController::class, 'store']);

    Route::post('/comment/{id}/vote', VoteUser::class);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

Route::post('/auth/login', LoginUser::class);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);