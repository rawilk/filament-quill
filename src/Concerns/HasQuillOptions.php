<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Concerns;

use Closure;

/**
 * @mixin \Filament\Forms\Components\Field
 * @mixin \Rawilk\FilamentQuill\Concerns\HasQuillToolbar
 */
trait HasQuillOptions
{
    protected string|Closure|null $theme = null;

    protected string|Closure|null $onTextChangeHandler = null;

    protected string|Closure|null $onInitCallback = null;

    public function useTheme(string|Closure|null $theme = null): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function onTextChange(string|Closure|null $handler = null): static
    {
        $this->onTextChangeHandler = $handler;

        return $this;
    }

    public function onInit(string|Closure|null $callback = null): static
    {
        $this->onInitCallback = $callback;

        return $this;
    }

    public function getTheme(): string
    {
        return $this->evaluate($this->theme) ?? config('filament-quill.default_theme', 'snow');
    }

    public function getOnTextChangeHandler(): ?string
    {
        return $this->evaluate($this->onTextChangeHandler);
    }

    public function getOnInitCallback(): ?string
    {
        return $this->evaluate($this->onInitCallback);
    }

    public function getQuillOptions(): array
    {
        return [
            'theme' => $this->getTheme(),
            'fonts' => $this->getFonts(),
            'fontSizes' => $this->getfontSizes(),
            'autofocus' => $this->isAutofocused(),
        ];
    }
}
