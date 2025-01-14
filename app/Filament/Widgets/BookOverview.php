<?php

namespace App\Filament\Widgets;

use App\Models\Author;
use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Books' ,  Book::query()->count()),
            Stat::make('Total Authors' ,  Author::query()->count()),
        ];
    }
}
