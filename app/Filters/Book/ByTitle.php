<?php

declare(strict_types=1);

namespace App\Filters\Book;

use Closure;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByTitle
{
    public function __construct(public Request $request)
    {
    }

    public function handle(Builder $query, Closure $next)
    {
        return $next($query)
            ->when($this->request->has('title'),
                fn($query) => $query->where('title', 'LIKE', '%'.$this->request->get('title').'%')
            );
    }
}