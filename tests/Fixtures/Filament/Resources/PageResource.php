<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources;

use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages\CreatePage;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages\EditPage;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages\ListPages;
use Rawilk\FilamentQuill\Tests\Fixtures\Models\Page;

final class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title'),
                QuillEditor::make('content'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
