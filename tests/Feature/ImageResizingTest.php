<?php

declare(strict_types=1);

use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;

it('does not allow image resizing by default', function () {
    $field = QuillEditor::make('content');

    expect($field->shouldAllowImageResizing())->toBeFalse()
        ->and($field->getQuillOptions())->toHaveKey('allowImageResizing', false);
});

it('can enable image resizing on the field', function () {
    $field = QuillEditor::make('content')->allowImageResizing();

    expect($field->shouldAllowImageResizing())->toBeTrue()
        ->and($field->getQuillOptions())->toHaveKey('allowImageResizing', true);
});

it('uses the config value as the default for image resizing', function () {
    config()->set('filament-quill.allow_image_resizing', true);

    expect(QuillEditor::make('content')->shouldAllowImageResizing())->toBeTrue();
});

it('allows the field to override the config default', function () {
    config()->set('filament-quill.allow_image_resizing', true);

    expect(QuillEditor::make('content')->allowImageResizing(false)->shouldAllowImageResizing())->toBeFalse();
});

it('accepts a closure for the image resizing condition', function () {
    $field = QuillEditor::make('content')->allowImageResizing(fn () => true);

    expect($field->shouldAllowImageResizing())->toBeTrue();
});
