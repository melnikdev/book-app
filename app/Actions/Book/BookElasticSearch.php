<?php

declare(strict_types=1);

namespace App\Actions\Book;

use App\Filters\Book\ByAuthor;
use App\Filters\Book\ByTitle;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class BookElasticSearch
{
    use AsAction, ApiResponses;

    public function handle(Request $request)
    {
        if ($request->has('description')) {
            return Book::search('description:'.$request->get('description'))->paginate();
        }
        return Book::query()->paginate();
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $books = $this->handle($request);
        return $this->successResponse(BookResource::collection($books)->response()->getData());
    }
}
