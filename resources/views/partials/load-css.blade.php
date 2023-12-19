@php
    $cssSrc = \Filament\Support\Facades\FilamentAsset::getStyleHref('quill', package: \Rawilk\FilamentQuill\FilamentQuillServiceProvider::PACKAGE_ID);
@endphp

data-dispatch="quill-loaded"
x-load-css="[@js($cssSrc)]"
x-on:quill-loaded-css.window.once="() => {
    if (window.__quillStylesLoaded === true) { return }
    const style = document.head.querySelector('link[href=\'{{ $cssSrc }}\']');
    style && style.remove();
    style && document.head.prepend(style);
    window.__quillStylesLoaded = true;
}"
