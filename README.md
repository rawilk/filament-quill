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
