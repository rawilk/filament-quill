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
        }
        JS,
        '@quill-images-deleted' => <<<'JS'
        ({ detail: { urls, statePath } }) => {
            // handle the upload here.
        }
        JS,
    ])
```

You could alternatively provide a callback to the `saveUploadedFileAttachmentsUsing()` method on the editor to help you track the files, however that route may require more work on your end.

**Note:** You may want to delay deleting the images from the server when listening to the `quill-images-deleted` event until the user triggers a save, and/or you reset the [history](#history) state of the editor.

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
