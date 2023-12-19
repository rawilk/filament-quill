<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Concerns;

use Closure;

trait InsertsPlaceholders
{
    protected array|Closure|null $placeholders = null;

    protected array $surroundPlaceholdersWith = [
        'start' => '[',
        'end' => ']',
    ];

    protected string|Closure|null $placeholderButtonLabel = null;

    public function placeholders(array|Closure|null $placeholders = null): static
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    public function surroundPlaceholdersWith(string|Closure $start = '[', string|Closure $end = ']'): static
    {
        $this->surroundPlaceholdersWith['start'] = $start;
        $this->surroundPlaceholdersWith['end'] = $end;

        return $this;
    }

    public function placeholderButtonLabel(string|Closure|null $label = null): static
    {
        $this->placeholderButtonLabel = $label;

        return $this;
    }

    public function getPlaceholders(): ?array
    {
        return $this->evaluate($this->placeholders);
    }

    public function getPlaceholderButtonLabel(): string
    {
        return $this->evaluate($this->placeholderButtonLabel) ?? __('filament-quill::quill.placeholders.label');
    }

    protected function getPlaceholdersHandler(): ?string
    {
        if (blank($this->getPlaceholders())) {
            return null;
        }

        $placeholderStart = $this->evaluate($this->surroundPlaceholdersWith['start']);
        $placeholderEnd = $this->evaluate($this->surroundPlaceholdersWith['end']);

        return <<<JS
        function (value) {
            const cursorPosition = this.quill.getSelection().index;
            const valueToInsert = `{$placeholderStart}\${value}{$placeholderEnd}`;
            this.quill.insertText(cursorPosition, valueToInsert);
            this.quill.setSelection(cursorPosition + valueToInsert.length);
        }
        JS;
    }
}
