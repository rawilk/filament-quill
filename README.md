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
