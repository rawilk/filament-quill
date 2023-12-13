<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Rawilk\FilamentQuill\FilamentQuillServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FilamentQuillServiceProvider::class,
        ];
    }
}
