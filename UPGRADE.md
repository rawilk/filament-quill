# Upgrade Guide

This guide covers the changes needed when upgrading to the current major version of `rawilk/filament-quill`.

## Requirements

Update your application to meet the new package constraints:

- PHP 8.3 or higher
- Laravel 12 or higher
- Filament 5

Then update the package:

```bash
composer require rawilk/filament-quill:^2.0
```

If you have published the package config, compare it with the latest config file and add any missing keys:

```bash
php artisan vendor:publish --tag="filament-quill-config" --force
```

The current config includes:

- `load_styles`
- `default_theme`
- `allow_image_resizing`

## Refresh Published Views And Translations

If you have published package views or translations, review your local copies against the new versions before upgrading in production.

```bash
php artisan vendor:publish --tag="filament-quill-views" --force
php artisan vendor:publish --tag="filament-quill-translations" --force
```

## Quill 2

This version uses Quill 2. If you provide custom JavaScript handlers with `handlers()`, `onInit()`, or `onTextChange()`, test them in the browser after upgrading.

Pay special attention to custom code that imports Quill internals or Parchment classes. Quill 2 changed some internal import paths and class names.

## Rendering Saved Content

If you render saved editor content outside the editor, add the package styles to your Tailwind CSS 4 theme stylesheet:

```css
@import "tailwindcss";

@source "<path-to-vendor>/rawilk/filament-quill/resources/**/*.blade.php";
@source inline("quill-content prose max-w-none dark:prose-invert");

@import "<path-to-vendor>/rawilk/filament-quill/resources/css/content.css";
```

If your theme stylesheet already imports Tailwind, add the package `@source` rules and `content.css` import to that existing stylesheet.

Render saved HTML inside a `quill-content prose max-w-none` wrapper:

```blade
@use(Illuminate\Support\HtmlString)

<div class="quill-content prose max-w-none">
    {{ new HtmlString($content) }}
</div>
```

If you support dark mode, add `dark:prose-invert`.

## Tailwind CSS 4

The package build now uses Tailwind CSS 4. Tailwind configuration now lives in CSS, so define package sources, generated classes, fonts, and theme tokens in your stylesheet with directives like `@source`, `@source inline(...)`, `@plugin`, and `@theme`.

If you render saved content with `prose` classes, include Tailwind's typography plugin in your theme stylesheet:

```css
@plugin "@tailwindcss/typography";
```

If your app is still migrating to Tailwind CSS 4, update your PostCSS setup to use the Tailwind 4 plugin:

```js
module.exports = {
    plugins: {
        'postcss-import': {},
        '@tailwindcss/postcss': {},
        autoprefixer: {},
    },
};
```

## Image Resizing

Image resizing is available but disabled by default. Enable it per editor:

```php
QuillEditor::make('content')
    ->allowImageResizing()
```

Or globally in `config/filament-quill.php`:

```php
'allow_image_resizing' => true,
```

Resized image dimensions are stored as `width` and `height` attributes. Alignment is stored as `data-align`, and the rendered alignment comes from the package content CSS.

## Toolbar Buttons

The default toolbar now includes the `ToolbarButton::Header` button. If you customize toolbar behavior with `disableToolbarButtons()` or `enableToolbarButtons()`, review the resulting toolbar after upgrading.
