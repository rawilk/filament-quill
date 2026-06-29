<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Rawilk\FilamentQuill\Filament\Forms\Components\QuillEditor;
use Rawilk\FilamentQuill\Tests\Fixtures\Models\Page;

final class EditorForm extends Livewire
{
    public ?string $savedContent = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->model(Page::class)
            ->schema([
                TextInput::make('title')
                    ->default('Browser test page'),
                QuillEditor::make('content')
                    ->placeholder('Start writing...')
                    ->placeholders([
                        'first_name',
                        'course_name',
                    ])
                    ->minHeight('180px'),
            ]);
    }

    public function save(): void
    {
        $this->savedContent = $this->form->getState()['content'] ?? null;
    }

    public function render(): View
    {
        return view('browser-editor-form');
    }
}
