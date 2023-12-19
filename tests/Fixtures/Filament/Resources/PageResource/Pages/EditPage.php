<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource;

final class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
