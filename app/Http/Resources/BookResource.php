<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Book",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", example: "Sample Book Title"),
        new OA\Property(property: "published_date", type: "string", format: "date", example: "2023-10-01"),
//        new OA\Property(
//            property: "authors",
//            type: "array",
//            items: new OA\Items(ref: "#/components/schemas/Author")
//        ),
//        new OA\Property(
//            property: "comments",
//            type: "array",
//            items: new OA\Items(ref: "#/components/schemas/Comment")
//        ),
    ]
)]
class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'published_date' => $this->published_date,
            'description' => $this->description,
            'authors' => AuthorResource::collection($this->authors),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
