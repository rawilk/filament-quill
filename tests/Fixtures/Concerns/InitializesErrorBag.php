<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Concerns;

trait InitializesErrorBag
{
    public function getErrorBag()
    {
        return parent::getErrorBag() ?? $this->resetErrorBag();
    }
}
