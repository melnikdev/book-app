<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    public function show(int $id): BookResource
    {
        $book = Book::query()->with(['authors', 'comments.user'])->findOrFail($id);
        return new BookResource($book);
    }
}
