<?php

declare(strict_types=1);

namespace App\Actions\Book;

use App\Filters\Book\ByAuthor;
use App\Filters\Book\ByTitle;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Pipeline;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class BookSearch
{
    use AsAction, ApiResponses;

    public function handle()
    {
        $pipelines = [
            ByTitle::class,
            ByAuthor::class
        ];

        return Pipeline::send(Book::query()->with(['authors', 'comments.user']))
            ->through($pipelines)
            ->thenReturn()
            ->paginate();
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $books = $this->handle();
        return $this->successResponse(BookResource::collection($books)->response()->getData());
    }
}
