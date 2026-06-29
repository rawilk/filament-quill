<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Font;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\ViewErrorBag;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\FilamentQuill\FilamentQuillServiceProvider;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\AdminPanelProvider;
use Rawilk\FilamentQuill\Tests\Fixtures\Livewire\EditorForm;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            AdminPanelProvider::class,
            FilamentQuillServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Fixtures/database/migrations');
    }

    protected function defineWebRoutes($router): void
    {
        $router->get('/quill-browser-test', fn () => view('browser-editor'))
            ->name('quill-browser-test');

        $router->get('/js/{package}/{id}.js', function (string $package, string $id): Response {
            $package = str($package)->beforeLast('/components')->toString();

            $asset = collect([
                ...FilamentAsset::getScripts(),
                ...FilamentAsset::getAlpineComponents(),
            ])->first(
                fn (Js|AlpineComponent $asset): bool => $asset->getId() === $id && $asset->getPackage() === $package,
            );

            abort_unless($asset instanceof Js || $asset instanceof AlpineComponent, 404);

            return response(file_get_contents($asset->getPath()), 200, [
                'Content-Type' => 'application/javascript',
            ]);
        })->where('package', '.*');

        $router->get('/css/{package}/{id}.css', function (string $package, string $id): Response {
            $asset = collect([
                ...FilamentAsset::getStyles(),
                ...FilamentAsset::getThemes(),
            ])
                ->first(fn (Css|Theme $asset): bool => $asset->getId() === $id && $asset->getPackage() === $package);

            abort_unless($asset instanceof Css || $asset instanceof Theme, 404);

            return response(file_get_contents($asset->getPath()), 200, [
                'Content-Type' => 'text/css',
            ]);
        })->where('package', '.*');

        $router->get('/fonts/{package}/{id}/{file}', function (string $package, string $id, string $file): Response {
            $asset = collect(FilamentAsset::getFonts())
                ->first(fn (Font $asset): bool => $asset->getId() === $id && $asset->getPackage() === $package);

            abort_unless($asset instanceof Font, 404);

            $path = $asset->getPath() . DIRECTORY_SEPARATOR . $file;

            abort_unless(is_file($path), 404);

            return response(file_get_contents($path), 200, [
                'Content-Type' => str($file)->endsWith('.css') ? 'text/css' : 'font/woff2',
            ]);
        })->where('package', '.*');
    }

    protected function getEnvironmentSetUp($app): void
    {
        Livewire::component('editor-form', EditorForm::class);

        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__ . '/Fixtures/resources/views',
        ]);
    }
}
