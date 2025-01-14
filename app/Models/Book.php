<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'published_date',
    ];
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

}
