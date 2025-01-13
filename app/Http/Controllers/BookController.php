<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    public function show(int $id): BookResource
    {
        $book = Book::query()->with('authors')->findOrFail($id);
        return new BookResource($book);
    }
}
