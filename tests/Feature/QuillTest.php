<?php

declare(strict_types=1);

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
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
