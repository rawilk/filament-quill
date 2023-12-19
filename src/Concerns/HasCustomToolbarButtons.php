<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Concerns;

use Closure;

trait HasCustomToolbarButtons
{
    protected array $customToolbarButtons = [];

    public function addToolbarButton(
        string $name,
        string $label,
        string|Closure $handler,
        array|null|Closure $options = null,
        bool $showSelectedOption = true,
    ): static {
        $this->customToolbarButtons[$name] = [
            'label' => $label,
            'options' => $options,
            'showSelected' => $showSelectedOption,
        ];

        $this->handlers[$name] = $this->evaluate($handler);

        return $this;
    }

    public function getCustomToolbarButtons(): array
    {
        return collect($this->customToolbarButtons)
            ->transform(function (array $button) {
                $button['options'] = $this->evaluate($button['options']);

                return $button;
            })
            ->toArray();
    }
}
