<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    use ApiResponses;

    #[OA\Get(
        path: '/api/v1/books/{id}',
        summary: 'Get details of a specific book',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the book',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(ref: '#/components/schemas/Book')
            ),
            new OA\Response(
                response: 404,
                description: 'Book not found'
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $book = Book::query()->with(['authors', 'comments.user'])->find($id);
        if (! $book) {
            return $this->notFoundResponse([], 'Book not found');
        }
        return $this->successResponse(new BookResource($book));
    }

    #[OA\Get(
        path: '/api/v1/books',
        summary: 'Get a list of books',
        tags: ['Books'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Book')
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $books = Book::query()->with(['authors', 'comments.user'])->paginate();
        return $this->successResponse(BookResource::collection($books)->toResponse(request())->getData());
    }
}
