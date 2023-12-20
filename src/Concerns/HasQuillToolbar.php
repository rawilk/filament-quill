<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Concerns;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @mixin \Filament\Forms\Components\Field
 */
trait HasQuillToolbar
{
    protected array|Closure|null $fonts = null;

    protected array|Closure|null $textColors = null;

    protected array|Closure|null $backgroundColors = null;

    protected array|Closure|null $handlers = null;

    protected array|Closure|null $fontSizes = [
        '8px',
        '9px',
        '10px',
        '11px',
        '12px',
        '14px',
        '16px',
        '18px',
        '20px',
        '24px',
        '28px',
        '32px',
        '40px',
    ];

    protected string|Closure|null $defaultFontSize = '14px';

    public function useFonts(array|Closure|null $fonts = null): static
    {
        $this->fonts = $fonts;

        return $this;
    }

    public function textColors(array|Closure|null $colors = null): static
    {
        $this->textColors = $colors;

        return $this;
    }

    public function backgroundColors(array|Closure|null $colors = null): static
    {
        $this->backgroundColors = $colors;

        return $this;
    }

    public function handlers(array|Closure|null $handlers = null): static
    {
        $this->handlers = $handlers;

        return $this;
    }

    public function fontSizes(array|Closure|null $fontSizes = null): static
    {
        $this->fontSizes = $fontSizes;

        return $this;
    }

    public function defaultFontSize(string|Closure|null $defaultFontSize = null): static
    {
        $this->defaultFontSize = $defaultFontSize;

        return $this;
    }

    public function getFonts(): ?array
    {
        return $this->evaluate($this->fonts);
    }

    public function getTextColors(): ?array
    {
        return $this->evaluate($this->textColors);
    }

    public function getBackgroundColors(): ?array
    {
        return $this->evaluate($this->backgroundColors);
    }

    public function mapFontName(string $font): string
    {
        return Str::slug($font);
    }

    public function getHandlers(): array
    {
        return [
            ...$this->getDefaultHandlers(),
            ...$this->evaluate($this->handlers) ?? [],
        ];
    }

    public function getFontSizes(): ?array
    {
        return $this->evaluate($this->fontSizes);
    }

    public function getDefaultFontSize(): ?string
    {
        return $this->evaluate($this->defaultFontSize);
    }

    /**
     * @return array<string>
     */
    protected function mapToolbarButtonsToString(array $buttons): array
    {
        return Arr::map(
            $buttons,
            fn ($button) => is_string($button) ? $button : $button->value,
        );
    }

    protected function getDefaultHandlers(): array
    {
        return array_filter([
            'placeholders' => $this->getPlaceholdersHandler(),
        ]);
    }
}
