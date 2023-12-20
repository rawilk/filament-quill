@php
    use Rawilk\FilamentQuill\Enums\ToolbarButton;

    $textColors = $getTextColors();
    $backgroundColors = $getBackgroundColors();
    $placeholders = $getPlaceholders();
@endphp

@if ($hasStickyToolbar)
    {{--
        To prevent the "sticky" class being added/removed rapidly with the intersection
        observer, we're going to attach the observer to a dummy element instead.
    --}}
    <div x-ref="stickyToolbar" class="w-0 h-0" wire:ignore></div>
@endif

<div
    id="quill-toolbar-{{ $id }}"
    x-ref="toolbar"
    @class([
        'fi-quill-toolbar',
    ])
    wire:ignore
>
    @if ($hasToolbarButton([ToolbarButton::Font, ToolbarButton::Size]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::Font))
                <select class="ql-font" title="{{ __('filament-quill::quill.toolbar.font') }}">
                    @if (is_array($fonts) && count($fonts))
                        <option></option>
                        @foreach ($fonts as $font)
                            <option value="{{ $mapFontName($font) }}" data-label="{{ $font }}"></option>
                        @endforeach
                    @endif
                </select>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Size))
                <select class="ql-size" title="{{ __('filament-quill::quill.toolbar.size') }}">
                    @foreach ($fontSizes as $fontSize)
                        <option value="{{ $fontSize }}" data-label="{{ $fontSize }}" @selected($fontSize === $defaultFontSize)></option>
                    @endforeach
                </select>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton([ToolbarButton::Bold, ToolbarButton::Italic, ToolbarButton::Underline, ToolbarButton::Strike]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::Bold))
                <button class="ql-bold" title="{{ __('filament-quill::quill.toolbar.bold') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Italic))
                <button class="ql-italic" title="{{ __('filament-quill::quill.toolbar.italic') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Underline))
                <button class="ql-underline" title="{{ __('filament-quill::quill.toolbar.underline') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Strike))
                <button class="ql-strike" title="{{ __('filament-quill::quill.toolbar.strike') }}"></button>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton([ToolbarButton::TextColor, ToolbarButton::BackgroundColor]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::TextColor))
                <select class="ql-color" title="{{ __('filament-quill::quill.toolbar.color') }}">
                    @if (is_array($textColors) && count($textColors))
                        @foreach ($textColors as $color)
                            <option value="{{ $color }}"></option>
                        @endforeach
                    @endif
                </select>
            @endif

            @if ($hasToolbarButton(ToolbarButton::BackgroundColor))
                <select class="ql-background" title="{{ __('filament-quill::quill.toolbar.background') }}">
                    @if (is_array($backgroundColors) && count($backgroundColors))
                        @foreach ($backgroundColors as $color)
                            <option value="{{ $color }}"></option>
                        @endforeach
                    @endif
                </select>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton(ToolbarButton::Scripts))
        <x-filament-quill::toolbar-group>
            <button class="ql-script" value="sub" title="{{ __('filament-quill::quill.toolbar.sub') }}"></button>
            <button class="ql-script" value="super" title="{{ __('filament-quill::quill.toolbar.super') }}"></button>
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton([ToolbarButton::BlockQuote, ToolbarButton::CodeBlock]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::BlockQuote))
                <button class="ql-blockquote" title="{{ __('filament-quill::quill.toolbar.blockquote') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::CodeBlock))
                <button class="ql-code-block" title="{{ __('filament-quill::quill.toolbar.code') }}"></button>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton([ToolbarButton::OrderedList, ToolbarButton::UnorderedList, ToolbarButton::Indent]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::OrderedList))
                <button class="ql-list" value="ordered" title="{{ __('filament-quill::quill.toolbar.ol') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::UnorderedList))
                <button class="ql-list" value="bullet" title="{{ __('filament-quill::quill.toolbar.ul') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Indent))
                <button class="ql-indent" value="-1" title="{{ __('filament-quill::quill.toolbar.outdent') }}"></button>
                <button class="ql-indent" value="+1" title="{{ __('filament-quill::quill.toolbar.indent') }}"></button>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton(ToolbarButton::TextAlign))
        <x-filament-quill::toolbar-group>
            <select class="ql-align" title="{{ __('filament-quill::quill.toolbar.text_align') }}">
                 <option selected="selected"></option>
                 <option value="center"></option>
                 <option value="right"></option>
                 <option value="justify"></option>
            </select>
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton([ToolbarButton::Link, ToolbarButton::Image]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::Link))
                <button class="ql-link" title="{{ __('filament-quill::quill.toolbar.link') }}"></button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Image))
                <button class="ql-image" title="{{ __('filament-quill::quill.toolbar.image') }}"></button>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton(ToolbarButton::ClearFormat))
        <x-filament-quill::toolbar-group>
            <button class="ql-clean" title="{{ __('filament-quill::quill.toolbar.clean') }}"></button>
        </x-filament-quill::toolbar-group>
    @endif

    @if ($hasToolbarButton([ToolbarButton::Undo, ToolbarButton::Redo]))
        <x-filament-quill::toolbar-group>
            @if ($hasToolbarButton(ToolbarButton::Undo))
                <button class="ql-undo" title="{{ __('filament-quill::quill.toolbar.undo') }}">
                    <svg
                        class="w-4 h-4 dark:fill-current"
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="rotate-left"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="currentColor"
                            d="M480 256c0 123.4-100.5 223.9-223.9 223.9c-48.84 0-95.17-15.58-134.2-44.86c-14.12-10.59-16.97-30.66-6.375-44.81c10.59-14.12 30.62-16.94 44.81-6.375c27.84 20.91 61 31.94 95.88 31.94C344.3 415.8 416 344.1 416 256s-71.69-159.8-159.8-159.8c-37.46 0-73.09 13.49-101.3 36.64l45.12 45.14c17.01 17.02 4.955 46.1-19.1 46.1H35.17C24.58 224.1 16 215.5 16 204.9V59.04c0-24.04 29.07-36.08 46.07-19.07l47.6 47.63C149.9 52.71 201.5 32.11 256.1 32.11C379.5 32.11 480 132.6 480 256z"
                        ></path>
                    </svg>
                </button>
            @endif

            @if ($hasToolbarButton(ToolbarButton::Redo))
                <button class="ql-redo" title="{{ __('filament-quill::quill.toolbar.redo') }}">
                    <svg
                        class="w-4 h-4 dark:fill-current"
                        aria-hidden="true"
                        focusable="false"
                        data-prefix="fas"
                        data-icon="rotate-right"
                        role="img"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"
                    >
                        <path
                            fill="currentColor"
                            d="M468.9 32.11c13.87 0 27.18 10.77 27.18 27.04v145.9c0 10.59-8.584 19.17-19.17 19.17h-145.7c-16.28 0-27.06-13.32-27.06-27.2c0-6.634 2.461-13.4 7.96-18.9l45.12-45.14c-28.22-23.14-63.85-36.64-101.3-36.64c-88.09 0-159.8 71.69-159.8 159.8S167.8 415.9 255.9 415.9c73.14 0 89.44-38.31 115.1-38.31c18.48 0 31.97 15.04 31.97 31.96c0 35.04-81.59 70.41-147 70.41c-123.4 0-223.9-100.5-223.9-223.9S132.6 32.44 256 32.44c54.6 0 106.2 20.39 146.4 55.26l47.6-47.63C455.5 34.57 462.3 32.11 468.9 32.11z"
                        ></path>
                    </svg>
                </button>
            @endif
        </x-filament-quill::toolbar-group>
    @endif

    @if (is_array($placeholders) && count($placeholders))
        <x-filament-quill::toolbar-group
            @style([
                '--ql-button-label: ' . \Illuminate\Support\Js::from($getPlaceholderButtonLabel())
            ])
        >
            <select class="ql-placeholders">
                {{-- empty option here so the editor doesn't auto select the first option --}}
                <option></option>
                @foreach ($placeholders as $placeholder)
                    <option value="{{ $placeholder }}"></option>
                @endforeach
            </select>
        </x-filament-quill::toolbar-group>
    @endif

    @foreach ($getCustomToolbarButtons() as $name => $button)
        <x-filament-quill::toolbar-group
            class="ql-formats-custom"
            @style([
                '--ql-button-label: ' . \Illuminate\Support\Js::from($button['label']),
            ])
        >
            @php
                $options = $button['options'] ?? [];
            @endphp

            @if (filled($options))
                <select
                    @class([
                        "ql-{$name}",
                        'ql-custom',
                        'ql-not-selectable' => ! $button['showSelected'],
                    ])
                >
                    @foreach ($options as $optionValue => $optionLabel)
                        <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                    @endforeach
                </select>
            @else
                <button class="ql-{{ $name }} ql-button ql-custom"></button>
            @endif
        </x-filament-quill::toolbar-group>
    @endforeach
</div>
