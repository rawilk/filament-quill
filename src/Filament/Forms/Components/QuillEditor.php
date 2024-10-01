<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Contracts\CanBeLengthConstrained;
use Filament\Forms\Components\Contracts\HasFileAttachments;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Rawilk\FilamentQuill\Concerns\HasCustomToolbarButtons;
use Rawilk\FilamentQuill\Concerns\HasQuillOptions;
use Rawilk\FilamentQuill\Concerns\HasQuillToolbar;
use Rawilk\FilamentQuill\Concerns\InsertsPlaceholders;
use Rawilk\FilamentQuill\Enums\ToolbarButton;

class QuillEditor extends Field implements CanBeLengthConstrained, HasFileAttachments
{
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasFileAttachments;
    use Concerns\HasPlaceholder;
    use Concerns\InteractsWithToolbarButtons;
    use HasCustomToolbarButtons;
    use HasExtraAlpineAttributes;
    use HasQuillOptions;
    use HasQuillToolbar;
    use InsertsPlaceholders;

    protected string $view = 'filament-quill::quill-editor';

    protected ?bool $shouldLoadStyles = null;

    protected string|Closure|null $minHeight = '200px';

    protected ?array $cachedToolbarButtons = null;

    protected bool|Closure|null $stickyToolbar = true;

    // Default toolbar
    protected array|Closure $toolbarButtons = [
        ToolbarButton::Font,
        ToolbarButton::Size,
        ToolbarButton::Bold,
        ToolbarButton::Italic,
        ToolbarButton::Underline,
        ToolbarButton::Strike,
        ToolbarButton::BlockQuote,
        ToolbarButton::OrderedList,
        ToolbarButton::UnorderedList,
        ToolbarButton::Indent,
        ToolbarButton::Link,
        ToolbarButton::Image,
        ToolbarButton::Scripts,
        ToolbarButton::TextAlign,
        ToolbarButton::TextColor,
        ToolbarButton::BackgroundColor,
        ToolbarButton::Undo,
        ToolbarButton::Redo,
        ToolbarButton::ClearFormat,
        ToolbarButton::Header,
    ];

    public function loadStyles(?bool $condition = true): static
    {
        $this->shouldLoadStyles = $condition;

        return $this;
    }

    public function minHeight(string|Closure|null $minHeight): static
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    public function shouldLoadStyles(): bool
    {
        return $this->shouldLoadStyles ?? config('filament-quill.load_styles', true);
    }

    public function stickyToolbar(bool|Closure|null $condition = true): static
    {
        $this->stickyToolbar = $condition;

        return $this;
    }

    public function getMinHeight(): ?string
    {
        return $this->evaluate($this->minHeight);
    }

    public function disableToolbarButtons(array $buttonsToDisable = []): static
    {
        $buttonsToDisable = $this->mapToolbarButtonsToString($buttonsToDisable);

        $this->cachedToolbarButtons = $this->toolbarButtons = array_values(array_filter(
            $this->getToolbarButtons(),
            static fn ($button) => ! in_array($button, $buttonsToDisable, true),
        ));

        return $this;
    }

    public function enableToolbarButtons(array $buttonsToEnable = []): static
    {
        $this->toolbarButtons = [
            ...$this->getToolbarButtons(),
            ...$buttonsToEnable,
        ];

        $this->cachedToolbarButtons = null;

        return $this;
    }

    public function clearHistory(): void
    {
        $this->getLivewire()->dispatch('quill-history-clear', id: $this->getStatePath());
    }

    public function getToolbarButtons(): array
    {
        if (is_array($this->cachedToolbarButtons)) {
            return $this->cachedToolbarButtons;
        }

        return $this->cachedToolbarButtons = $this->mapToolbarButtonsToString(
            $this->evaluate($this->toolbarButtons),
        );
    }

    public function hasToolbarButton(string|array|ToolbarButton $button): bool
    {
        if (is_array($button)) {
            $buttons = $this->mapToolbarButtonsToString($button);

            return (bool) count(array_intersect($buttons, $this->getToolbarButtons()));
        }

        $buttonValue = is_string($button) ? $button : $button->value;

        return in_array($buttonValue, $this->getToolbarButtons(), true);
    }

    public function hasStickyToolbar(): bool
    {
        return $this->evaluate($this->stickyToolbar) === true;
    }
}
