<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Load Stylesheet
    |--------------------------------------------------------------------------
    |
    | In most cases it should be fine to load the plugin's stylesheet
    | automatically, however in some cases this may not be desirable.
    | Set this value to `false` to prevent us from automatically injecting
    | our stylesheet into the DOM.
    |
    */
    'load_styles' => true,

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Here you may define a default theme to use for the quill editor.
    | This can be changed on a per-editor basis.
    |
    | Quill officially supports two themes: `snow` and `bubble`
    | @see: https://quilljs.com/docs/themes
    |
    | Note: We have currently only styled and optimized for the `snow`
    | theme, so you may need to style the `bubble` theme yourself.
    | PR's are always welcome.
    |
    */
    'default_theme' => 'snow',

    /*
    |--------------------------------------------------------------------------
    | Allow Image Resizing
    |--------------------------------------------------------------------------
    |
    | When enabled, images inside the editor can be resized via drag handles
    | and aligned (left / center / right) using an overlay toolbar. This can
    | also be toggled per-editor with the `allowImageResizing()` method.
    |
    | Resized dimensions are stored as `width`/`height` attributes and alignment
    | as a `data-align` attribute on the `<img>` tag, so the output HTML stays
    | clean and round-trips safely.
    |
    */
    'allow_image_resizing' => false,
];
