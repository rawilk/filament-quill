@php
    use Filament\Support\Facades\FilamentAsset;
    use Filament\Support\Facades\FilamentView;
    use Rawilk\FilamentQuill\FilamentQuillServiceProvider;

    $id = $getId();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $fonts = $getFonts();
    $fontSizes = $getFontSizes();
    $defaultFontSize = $getDefaultFontSize();
    $textChangeHandler = $getOnTextChangeHandler();
    $onInitCallback = $getOnInitCallback();
    $hasHistory = $hasToolbarButton([\Rawilk\FilamentQuill\Enums\ToolbarButton::Undo, \Rawilk\FilamentQuill\Enums\ToolbarButton::Redo]);
    $hasStickyToolbar = $hasStickyToolbar();

    // To make our `prefer-lowest` tests pass, we're checking if the panel has `spa` mode enabled
    // here like this instead, since some earlier versions of filament 3.0 don't appear
    // to have this method defined.
    $hasSpaMode = rescue(fn () => FilamentView::hasSpaMode(), fn () => false);

    $fontSizeStyle = filled($fontSizes)
        ? [
            '--ql-default-size: ' . $defaultFontSize,
        ]
        : [];
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        @style([
            ...$fontSizeStyle,
        ])
        x-data

        @if ($shouldLoadStyles())
            x-load-css="[@js(FilamentAsset::getStyleHref('quill', package: FilamentQuillServiceProvider::PACKAGE_ID))]"
            data-css-before="filament"
        @endif
    >
        @if ($isDisabled)
            <div
                x-data="{
                    state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
                }"
                x-html="state"
                class="fi-quill fi-disabled quill-content prose max-w-none dark:prose-invert block w-full rounded-lg bg-gray-50 px-3 py-3 shadow-sm ring-1 ring-gray-950/10 dark:bg-transparent dark:ring-white/10 text-gray-500 dark:text-gray-400"
            ></div>
        @else
            <x-filament::input.wrapper
                :valid="! $errors->has($statePath)"
                :attributes="
                    \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                        ->class(['fi-quill max-w-full'])
                "
            >
                <div
                    @if ($hasSpaMode)
                        ax-load="visible"
                    @else
                        ax-load
                    @endif
                    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('quill', package: FilamentQuillServiceProvider::PACKAGE_ID) }}"
                    x-data="quill({
                        state: $wire.{{ $applyStateBindingModifiers("entangle('{$statePath}')", isOptimisticallyLive: false) }},
                        statePath: '{{ $statePath }}',
                        placeholder: @js($getPlaceholder()),
                        options: @js($getQuillOptions()),
                        handlers: {
                            @foreach ($getHandlers() as $handlerKey => $handler)
                                @js($handlerKey): {{ $handler }},
                            @endforeach
                        },
                        @if (filled($textChangeHandler))
                            onTextChangedHandler: {{ $textChangeHandler }},
                        @endif
                        @if (filled($onInitCallback))
                            onInit: {{ $onInitCallback }},
                        @endif
                        wireId: @js($this->getId()),
                        allowImages: @js($hasToolbarButton(\Rawilk\FilamentQuill\Enums\ToolbarButton::Image)),
                        hasHistory: @js($hasHistory),
                        stickyToolbar: @js($hasStickyToolbar),
                    })"
                    @if ($hasHistory)
                        x-on:quill-history-clear.window="clearHistory"
                    @endif
                    x-ignore
                    data-quill-id="{{ $statePath }}"
                    @if ($isLiveDebounced())
                        x-on:input.debounce.{{ $getLiveDebounce() }}="$wire.call('$refresh')"
                    @endif
                    {{ $getExtraAlpineAttributeBag() }}
                    @style([
                        "--ql-min-height: {$getMinHeight()}",
                    ])
                >
                    @include('filament-quill::partials.toolbar')

                    <div class="quill-content" wire:ignore>
                        <div x-ref="quill" id="{{ $id }}"></div>
                    </div>

                    <input type="hidden" name="{{ $getName() }}" x-bind:value="state">
                </div>
            </x-filament::input.wrapper>
        @endif
    </div>
</x-dynamic-component>
