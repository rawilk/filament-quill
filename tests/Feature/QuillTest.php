<?php

declare(strict_types=1);

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Rawilk\FilamentQuill\Enums\ToolbarButton;
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages\CreatePage;
use Rawilk\FilamentQuill\Tests\Fixtures\Filament\Resources\PageResource\Pages\EditPage;
use Rawilk\FilamentQuill\Tests\Fixtures\Livewire\Livewire as LivewireFixture;
use Rawilk\FilamentQuill\Tests\Fixtures\Models\Page;

use function Pest\Livewire\livewire;

it('renders the field', function () {
    livewire(TestComponentWithForm::class)
        ->assertSuccessful()
        ->assertFormFieldExists('content');
});

test('content can be saved to new record', function () {
    $page = Page::factory()->make();

    livewire(CreatePage::class)
        ->fillForm([
            'title' => $page->title,
            'content' => $page->content,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Page::class, [
        'title' => $page->title,
        'content' => $page->content,
    ]);
});

test('content can be updated', function () {
    $page = Page::factory()->create();
    $newData = Page::factory()->make();

    livewire(EditPage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($page->refresh())
        ->title->toBe($newData->title)
        ->content->toBe($newData->content);
});

test('can see header button and has default headers set', function () {
    $livewire = livewire(TestComponentWithForm::class);

    $headersArray = ['1', '2', '3', '4', '5', '6', false];

    $quillEditor = $livewire->content()->form->getComponents()[1];

    expect($quillEditor->getToolbarButtons())
        ->toContain((ToolbarButton::Header)->value)
        ->and($quillEditor->getHeaders())
        ->toEqual($headersArray);

    $testArray = ['1', '2', '3'];

    $quillEditor->setHeaders($testArray);

    expect($quillEditor->getHeaders())
        ->toEqual($testArray);
});

test('can set available headers in quill editor', function () {
    $livewire = livewire(TestComponentWithForm::class);

    $quillEditor = $livewire->content()->form->getComponents()[1];

    $testArray = ['1', '2', '3'];

    $quillEditor->setHeaders($testArray);

    expect($quillEditor->getHeaders())
        ->toEqual($testArray);
});

class TestComponentWithForm extends LivewireFixture
{
    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->model(Page::class)
            ->schema([
                TextInput::make('title'),
                QuillEditor::make('content'),
            ]);
    }

    public function render(): View
    {
        return view('form');
    }
}
