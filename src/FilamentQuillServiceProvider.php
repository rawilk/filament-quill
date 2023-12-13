<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentQuillServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-quill')
            ->hasConfigFile()
            ->hasViews();
    }
}
