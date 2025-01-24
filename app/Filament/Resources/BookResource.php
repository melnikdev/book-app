<?php

namespace App\Filament\Resources;

use App\Filament\Exports\BookExporter;
use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\DatePicker::make('published_date')->required()->maxDate(now()),
                Forms\Components\Textarea::make('description'),
                Forms\Components\FileUpload::make('cover')
                    ->image()
                    ->imageEditor(),
                Forms\Components\Select::make('author_id')
                    ->multiple()
                    ->relationship('authors', 'last_name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(BookExporter::class)
            ])
            ->columns([
                TextColumn::make('id')->numeric()->sortable(),
                ImageColumn::make('cover')->width(100)->height(100),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('authors.last_name')->label('Author')->searchable()->sortable(),
                TextColumn::make('published_date')->label('Published Date')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    TextEntry::make('title')
                        ->weight(FontWeight::Bold),
                    TextEntry::make('description')
                        ->prose(),
                ]),
                Split::make([
                    Section::make([
                        ImageEntry::make('cover'),
                    ]),
                    Section::make([
                        TextEntry::make('authors.last_name')
                            ->prose(),
                        TextEntry::make('published_date')
                            ->date(),
                    ]),
                ])->from('sm')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
