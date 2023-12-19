<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentQuillServiceProvider extends PackageServiceProvider
{
    public const PACKAGE_ID = 'rawilk/filament-quill';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-quill')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        FilamentAsset::register(assets: [
            AlpineComponent::make('quill', __DIR__ . '/../resources/dist/filament-quill.js')
                ->loadedOnRequest(),

            Css::make('quill', __DIR__ . '/../resources/dist/filament-quill.css')
                ->loadedOnRequest(),
        ], package: self::PACKAGE_ID);
    }
}
