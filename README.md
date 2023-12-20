# filament-quill

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rawilk/filament-quill.svg?style=flat-square)](https://packagist.org/packages/rawilk/filament-quill)
![Tests](https://github.com/rawilk/filament-quill/workflows/Tests/badge.svg?style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/rawilk/filament-quill.svg?style=flat-square)](https://packagist.org/packages/rawilk/filament-quill)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rawilk/filament-quill?style=flat-square)](https://packagist.org/packages/rawilk/filament-quill)
[![License](https://img.shields.io/github/license/rawilk/filament-quill?style=flat-square)](https://github.com/rawilk/filament-quill/blob/main/LICENSE.md)

![social image](https://github.com/rawilk/filament-quill/blob/main/art/social-image.png?raw=true)

##

`filament-quill` offers a [Quill](https://quilljs.com) rich text editor integration for filament admin panels and forms.

## Installation

You can install the package via composer:

```bash
composer require rawilk/filament-quill
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-quill-config"
```

You can view the default configuration here: https://github.com/rawilk/filament-quill/blob/main/config/filament-quill.php

If you need to, you can publish the views and translations with:

```bash
php artisan vendor:publish --tag="filament-quill-views"
php artisan vendor:publish --tag="filament-quill-translatinos"
```

For more information on setup necessary to render editor content, see the [Rendering Content](#rendering-content) section.

## Usage

The editor has been set up to behave like and have a similar api to the rich text editor component provided by Filament. One major difference between Filament's editor and the package's editor is Filament is using Trix for the editor, while this package is using Quill.

Here's a quick example of how to use the editor in a filament form:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content'),
```

This will provide an editor that will look like this in your form:

![basic example](https://github.com/rawilk/filament-quill/blob/main/art/basic.png?raw=true)

## Toolbar Buttons

You may set the toolbar buttons for the editor using the `toolbarButtons()` method. The options shown here are the defaults. Please consult the `ToolbarButton` enum for a full
list of available toolbar buttons.

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Rawilk\FilamentQuill\Enums\ToolbarButton;

QuillEditor::make('content')
    ->toolbarButtons([
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
    ])
```

You may alternatively use the `disableToolbarButtons()` method to disable specific buttons:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Rawilk\FilamentQuill\Enums\ToolbarButton;

QuillEditor::make('content')
    ->disableToolbarButtons([
        ToolbarButton::BlockQuote,
        ToolbarButton::Font,
    ])
```

To completely disable all toolbar buttons, pass an empty array to `toolbarButtons([])`, or use the `disableAllToolbarButtons()` method.

You can also enable specific toolbar buttons using the `enableToolbarButtons()` method:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Rawilk\FilamentQuill\Enums\ToolbarButton;

QuillEditor::make('content')
    ->enableToolbarButtons([
        ToolbarButton::CodeBlock,
    ])
```

## Placeholders

A requirement I often have for rich text editors is the ability to provide a list of placeholder variables that an end-user can select and insert into the editor. My most common use case for this is for email templates. I've made it simple to do this in this package. All you need to do is provide an array of placeholders to the component.

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->placeholders([
        'USER_NAME',
        'USER_EMAIL',
        'CURRENT_DATE',
    ])
```

![placeholders example](https://github.com/rawilk/filament-quill/blob/main/art/placeholders.png?raw=true)

As you can see, we take care of adding the toolbar button and registering a [Handler](#handlers) to insert the placeholder variable into the editor for you. The editor will surround the variable with the `[` and `]` characters before the variable is inserted, however these characters can be [customized](#surrounding-characters).

**Note:** Parsing and replacing your variables in your content is outside the scope of this form component. You will need to handle that part yourself.

### Surrounding Characters

By default, we will surround a variable with `[` and `]` before it's inserted into the editor, so a the `USER_NAME` variable would become `[USER_NAME]` when we insert it.

To change these characters, you can use the `surroundPlaceholdersWith()` method:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->placeholders([
        'USER_NAME',
        'USER_EMAIL',
        'CURRENT_DATE',
    ])
    ->surroundPlaceholdersWith(start: '{{ ', end: ' }}')
```

Now when a variable is inserted, it will look like `{{ USER_NAME }}` instead.

### Placeholder Button Label

To change the text on the placeholder button, you can either modify the `filament-quill::quill.placeholders.label` translation, or you can pass in a label via the `placeholderButtonLabel()` method.

## Handlers

If you want to override a handler for an existing toolbar button, you can define your custom JavaScript [handlers](https://quilljs.com/docs/modules/toolbar/#handlers) using the `handlers()` method. Here's an example of how to use your own handler for the `bold` toolbar button:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->handlers([
        'bold' => <<<'JS'
        function (value) {
            if (value) {
                // this.quill.format(...);
            }
        }
        JS,
    ])
```

> Note: Inside your callback functions, you will have access to the quill editor instance via `this.quill` as long as you don't use an arrow function.

## Custom Toolbar Buttons

To add your own toolbar buttons, you can use the `addToolbarButton()` method. You will need to provide a name, a label, and a JavaScript handler for the button. If you need a dropdown instead, you will need to provide an array of options as well.

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->addToolbarButton(
        name: 'custom',
        label: 'Custom button',
        handler: <<<'JS'
        function (value) {
            console.log(value);
            // this.quill.insertText(0, value);
        },
        JS,
        // options: ['option 1', 'option 2'],
        // showSelectedOption: true,
    )
```

The last parameter, `showSelectedOption` only applies to dropdown buttons. When set to true, when a user clicks on an option, it will show the selected option's text as the dropdown label, just like the font family or font size toolbar buttons do.

## Uploading Images

When the `ToolbarButton::Image` button is enabled, a user will be able to insert an image into the editor. Similar to filament's rich text editor, we will upload the image to the server and use that image's url on the server instead of storing it as a base64 encoded image in the content. You can customize how and where the images are stored on the server using these methods:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->fileAttachmentsDisk('s3')
    ->fileAttachmentsDirectory('attachments')
    ->fileAttachmentsVisibility('private')
```

Note: We do not handle tracking and managing the uploaded images. For example, if an image is deleted from the content, we will not remove it from the server, so images have a high probability of becoming orphaned. We will dispatch a `quill-image-uploaded` alpine event when we upload an image, and a `quill-images-deleted` alpine event when our JavaScript detects an image has been removed from the content. Both of these events will receive the fully qualified urls of the relevant images, and the name of the field the event was dispatched from. You could listen for these events and track the absolute urls of the images:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->extraAlpineAttributes([
        '@quill-image-uploaded' => <<<'JS'
        ({ detail: { url, statePath } }) => {
            // handle the upload here.
            // console.log(url);
        }
        JS,
        '@quill-images-deleted' => <<<'JS'
        ({ detail: { urls, statePath } }) => {
            // handle the event here.
        }
        JS,
    ])
```

You could alternatively provide a callback to the `saveUploadedFileAttachmentsUsing()` method on the editor to help you track the files, however that route may require more work on your end.

**Note:** You may want to delay deleting the images from the server when listening to the `quill-images-deleted` event until the user triggers a save, and/or you reset the [history](#history) state of the editor.

## Rendering Content

To match the formatting you will see in the editor, you should wrap your user-generated content inside a container with the `quill-content prose max-w-none` classes on it. You will also need to make sure you have the styles for the content area from this package loaded as well. We've extracted those styles into a separate stylesheet, called `content.css`. Depending on how you're rendering the content, you may find it easier to bundle the `content.css` styles in with your theme's stylesheet. If you haven't set up a custom theme and are using a panel, you should follow the [Filament docs](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) first on how to do that.

The following will apply in both a panel and standalone as well.

1. In your stylesheet, import the content styles:

```css
@import "<path-to-vendor>/rawilk/filament-quill/resources/css/content.css";

/*
 * Alternatively, you may import the entire stylesheet, however that's not recommended
 * since Quill's editor styles are quite expensive, and we load the stylesheet necessary
 * for the editor automatically for you.
 */
/* @import '<path-to-vendor>/rawilk/filament-quill/resources/css/app.css'; */
```

2. Add the package's views to your `tailwind.config.js` file.

```js
content: [
    // ...
    '<path-to-vendor>/rawilk/filament-quill/resources/**/*.blade.php',
],

// In some cases, it is necessary to safelist the root element selector so tailwind
// doesn't purge everything.
safelist: [
    'quill-content',
],
```

3. Add the `tawilwindcss/nesting` plugin to your `postcss.config.js` file.

```js
module.exports = {
    plugins: {
        "tailwindcss/nesting": {},
        tailwindcss: {},
        autoprefixer: {},
    },
};
```

4. Rebuild your custom theme.

```bash
npm run build
```

5. Render the content

```html
@use(Illuminate\Support\HtmlString)

<div
    class="quill-content prose max-w-none"
    @style([
        // Adjust or omit as necessary depending on your default
        // font size for editor content.
        '--ql-default-size: 14px',
    ])
>
    {{ new HtmlString($yourContent) }}
</div>
```

You can also add `dark:prose-invert` to your container if you're supporting dark mode for the content rendering.

It's also generally a good idea to run your content through a html purifier, however that is outside the scope of these docs.

### Custom Fonts

If you have the `ToolbarButton::Font` button enabled, we will render a dropdown allowing the user to format their content with `Sans Serif`, `Serif`, or `Monospaced` font families. You will need to pull in and register those font families manually, however. In a panel, you could take advantage of the `panels::head.start` [Render Hook](https://filamentphp.com/docs/3.x/support/render-hooks) to accomplish this.

In the code below, we're going to pull in `Fira Code` and `PT Serif` monospace and serif fonts to use, however the process is similar to custom fonts as well.

```html
<link
    href="https://fonts.bunny.net/css?family=fira-code:300,400,500,600,700|pt-serif:400,400i,700,700i&display=swap"
    rel="stylesheet"
/>

<style>
    :root {
        --font-serif-family: "PT Serif";
        --font-mono-family: "Fira Code";
    }
</style>
```

#### Registering the font families

In the package's stylesheet, we configure monospace and serif font families to look for the `--font-serif-family` and `--font-mono-family` css variables in the editor area, however when rendering your own content independently, you'll need to configure your fonts in your theme's `tailwind.config.js` file.

```js
import defaultTheme from "tailwindcss/defaultTheme";

export default {
    // ...
    theme: {
        extend: {
            fontFamily: {
                serif: [
                    "var(--font-serif-family)",
                    ...defaultTheme.fontFamily.serif,
                ],
                mono: [
                    "var(--font-mono-family)",
                    ...defaultTheme.fontFamily.mono,
                ],
            },
        },
    },
};
```

#### Using custom font families

If you want to use custom font families, like "Times New Roman", or something like that, you can use the `useFonts()` method on the component:

> Be sure to follow the [Rendering Content](#rendering-content) section first to make sure you have everything setup for this.

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->useFonts([
        'Times New Roman',
    ])
```

Based on the [registering the font families](#registering-the-font-families) section, you will need to register the font in your tailwind config. We will map each font family value to a slug, so the "Times New Roman" font above will be mapped to "times-new-roman".

```js
fontFamily: {
    times: ['Times New Roman'],
}
```

In a custom stylesheet, you will need to target the areas of the content that are formatted with this font:

```css
.quill-content {
    &,
    .ql-editor {
        .ql-times-new-roman,
        .ql-editor .ql-times-new-roman {
            @apply font-times;
        }
    }
}
```

Be sure to replace `.ql-times-new-roman` and `font-times` with your actual font names.

### Custom font sizes

When the `ToolbarButton::Size` button is enabled, we will show a dropdown of font sizes the user can use to format their content with. Like with the [font families](#custom-fonts), you are free to define your own sizes. You can pass an array of font sizes to the `fontSizes` method:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->fontSizes([
        '10px',
        '12px',
        '14px',
        '20px',
    ])
```

When you provide actual CSS size units, Quill will inline the text size right on the content, so no additional styling will be required. However, if you provide non-standard sizes, like "Small" or "Large", you will need to target those selectors in your css. The selectors follow a scheme of: `ql-size-{size}`.

```css
.ql-size-small,
.ql-editor .ql-size-small {
    font-size: 0.75rem;
}
```

### Custom colors

When you have the `ToolbarButton::TextColor` and `ToolbarButton::BackgroundColor` buttons enabled, you are free to specify your own color pallet using css hex color codes.

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

$colors = [
    '#fff',
    '#ff0000',
    '#333',
    // ...
];

QuillEditor::make('content')
    ->textColors($colors)
    ->backgroundColors($colors)
```

## History

By default, the editor includes toolbar buttons for undo/redo history actions. When dealing with [Images](#uploading-images) or some other use-cases, you may want to reset the history state of the editor so the user can't "undo" a change back to a broken image if you removed it from the server. This can easily be accomplished by calling the `clearHistory` method on the component from an action, for example.

Here's an example of resetting the history state on an edit resource page form using the `afterSave` hook:

```php
use Filament\Forms\Components\Component;

protected function afterSave(): void
{
    $component = $this->form->getComponent('data.content');

    $component->clearHistory();
}
```

Behind the scenes, the editor component will dispatch the `quill-history-clear` browser event, which our javascript will be listening for. If you aren't able to get a component instance, you can manually dispatch the event yourself. You will just need to know the state path for the component (typically `data.your_field_name`).

```php
$this->dispatch('quill-history-clear', id: 'data.content');
```

## Other Callbacks

### onInit

Using the `onInit` callback, you are able to register additional handlers or callbacks on the quill editor instance, and more. This can be a great place to register your own [Event](https://quilljs.com/docs/api/#events) handlers on the editor instance. All you need to do is provide a JavaScript callback to the `onInit()` method:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->onInit(<<<'JS'
    function (quill, alpineInstance) {
        // quill.on('selection-change', function (range, oldRange, source) {
            // do stuff
        // )};
    }
    JS)
```

Our JavaScript will pass your callback an instance of the quill editor, as well as the alpine component instance.

### Text Changed

If you just want to hook into the `text-changed` event that is dispatched from quill, you can use the `onTextChange` method:

```php
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

QuillEditor::make('content')
    ->onTextChange(<<<'JS'
    function (delta, oldDelta, source, alpineInstance) {
        // handle it
    },
    JS)
```

Note that we're using a regular function here, and not an arrow function. This is so you can use `this.quill` for the editor instance.

In addition to the normal arguments that Quill provides, we also provide your callback an instance of the alpine component if you need it. You can prevent any processing of this event by our JavaScript if you return `false` from your callback.

## Scripts

### Setup

For convenience, you can run the setup bin script for easy installation for local development.

```bash
./bin/setup.sh
```

### Formatting

Although formatting is done automatically via workflow, you can format php code locally before committing with a composer script:

```bash
composer format
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

Please review [my security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

-   [Randall Wilk](https://github.com/rawilk)
-   [All Contributors](../../contributors)

## Alternatives

-   [Filament's Rich Editor](https://filamentphp.com/docs/3.x/forms/fields/rich-editor)
-   [Filament Tiptap Editor](https://github.com/awcodes/filament-tiptap-editor)
-   [Filament Forms TinyEditor](https://github.com/mohamedsabil83/filament-forms-tinyeditor)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
