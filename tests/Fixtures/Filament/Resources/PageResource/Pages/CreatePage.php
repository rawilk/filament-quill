<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rawilk\FilamentQuill\Tests\Fixtures\Concerns\InitializesErrorBag;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource;

final class CreatePage extends CreateRecord
{
    use InitializesErrorBag;

    protected static string $resource = PageResource::class;

    public function boot(): void
    {
        $this->resetErrorBag();
    }
}
