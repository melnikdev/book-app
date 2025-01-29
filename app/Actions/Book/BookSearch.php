<?php

namespace App\Actions\Book;

use App\Filters\Book\ByAuthor;
use App\Filters\Book\ByTitle;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Pipeline;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class BookSearch
{
    use AsAction;

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

    public function asController(ActionRequest $request): AnonymousResourceCollection
    {
        $books = $this->handle();

        return BookResource::collection($books);
    }
}
