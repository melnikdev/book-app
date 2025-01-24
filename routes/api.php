<?php

use App\Actions\Authentication\LoginUser;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/book/{id}', [BookController::class, 'show']);


Route::post('/auth/login', LoginUser::class);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');