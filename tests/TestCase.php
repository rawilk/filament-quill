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
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\FilamentQuill\FilamentQuillServiceProvider;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\AdminPanelProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;

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

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__ . '/Fixtures/resources/views',
        ]);
    }
}
