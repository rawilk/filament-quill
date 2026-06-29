<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Rawilk\FilamentQuill\Tests\Fixtures\Concerns\InitializesErrorBag;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource;

final class EditPage extends EditRecord
{
    use InitializesErrorBag;

    protected static string $resource = PageResource::class;

    public function boot(): void
    {
        $this->resetErrorBag();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
