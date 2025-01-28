<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class VoteUser
{
    use AsAction;

    public function handle(int $commentId)
    {
        $comment = Comment::query()->find($commentId);

        if (! $comment) {
            return ['data' => ['message' => 'Comment not found'], 'status' => 404];
        }

        $vote = $comment->votes()->where(['user_id' => Auth::id()])->first();

        if ($vote) {
            $vote->delete();
            $comment->decrement('rating');

            return ['data' => ['message' => 'Vote deleted'], 'status' => 200];
        }

        $comment->votes()->create(['user_id' => Auth::id()]);
        $comment->increment('rating');

        return ['data' => ['message' => 'Vote added'], 'status' => 201];
    }

    public function asController(int $commentId, ActionRequest $request): JsonResponse
    {
        $response = $this->handle($commentId);

        return response()->json($response['data'], $response['status']);
    }
}
