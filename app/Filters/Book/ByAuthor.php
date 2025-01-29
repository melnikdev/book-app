<?php

declare(strict_types=1);

namespace App\Filters\Book;

use Closure;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByAuthor
{
    public function __construct(public Request $request)
    {
    }

    public function handle(Builder $query, Closure $next)
    {
        return $next($query)
            ->when($this->request->has('author'),
                fn($query) => $query->whereHas('authors',
                    fn($query) => $query->where(
                        fn($query) => $query->where('first_name', 'LIKE', '%'.$this->request->get('author').'%')
                            ->orWhere('last_name', 'LIKE', '%'.$this->request->get('author').'%'))
                )
            );
    }
}