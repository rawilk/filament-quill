<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource;

final class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
