<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CommentBookUser
{
    use AsAction;

    public function rules(): array
    {
        return [
            'body' => 'required|max:255',
        ];
    }

    public function handle(int $bookId, array $comment): array
    {
        $book = Book::query()->find($bookId);

        if (! $book) {
            return ['data' => ['message' => 'Book not found'], 'status' => 404];
        }

        $book->comments()->create($comment);

        return ['data' => ['message' => 'Comment created'], 'status' => 201];
    }

    public function asController(int $bookId, ActionRequest $request): JsonResponse
    {
        $validated = $request->safe()->merge(['user_id' => Auth::id()]);
        $response = $this->handle($bookId, $validated->toArray());

        return response()->json($response['data'], $response['status']);
    }
}
