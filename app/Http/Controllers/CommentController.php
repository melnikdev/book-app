<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(int $id, StoreCommentRequest $request): JsonResponse
    {
        $book = Book::query()->find($id);

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $validated = $request->safe()->merge(['user_id' => Auth::id()]);

        $book->comments()->create($validated->toArray());

        return response()->json(['message' => 'Comment created']);
    }
}
