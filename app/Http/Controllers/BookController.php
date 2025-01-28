<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Filters\Book\ByTitle;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Pipeline;

class BookController extends Controller
{
    public function show(int $id): BookResource
    {
        $book = Book::query()->with(['authors', 'comments.user'])->findOrFail($id);
        return new BookResource($book);
    }

    public function index(): AnonymousResourceCollection
    {
        $books = Book::query()->with(['authors', 'comments.user'])->paginate();
        return BookResource::collection($books);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $pipelines = [
            ByTitle::class
        ];

        $books = Pipeline::send(Book::query()->with(['authors', 'comments.user']))
            ->through($pipelines)
            ->thenReturn()
            ->paginate();

        return BookResource::collection($books);
    }
}
