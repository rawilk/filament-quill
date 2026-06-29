<?php

declare(strict_types=1);

$editorHtml = 'document.querySelector(".ql-editor").innerHTML';

it('renders the editor toolbar and editable surface', function () {
    $page = visit(route('quill-browser-test'));

    $page
        ->assertSee('Content')
        ->assertPresent('.fi-quill')
        ->assertPresent('.fi-quill-toolbar')
        ->assertPresent('.ql-editor')
        ->assertVisible('.ql-bold')
        ->assertVisible('.ql-italic')
        ->assertVisible('.ql-link')
        ->assertVisible('.ql-picker.ql-header')
        ->assertScript('document.querySelector(".ql-editor").getAttribute("contenteditable")', 'true');
})->group('browser');

it('updates the editor html when typing rich text', function () use ($editorHtml) {
    $page = visit(route('quill-browser-test'));

    $page
        ->click('.ql-editor')
        ->typeSlowly('.ql-editor', 'Hello from the browser test', 10)
        ->assertScript('document.querySelector(".ql-editor").textContent.trim()', 'Hello from the browser test')
        ->assertScript($editorHtml, '<p>Hello from the browser test</p>');
})->group('browser');

it('applies toolbar formatting in the browser', function () use ($editorHtml) {
    $page = visit(route('quill-browser-test'));

    $page
        ->click('.ql-editor')
        ->click('.ql-bold')
        ->typeSlowly('.ql-editor', 'Important text', 10)
        ->assertScript('document.querySelector(".ql-editor strong")?.textContent', 'Important text')
        ->assertScript($editorHtml, '<p><strong>Important text</strong></p>');
})->group('browser');

it('inserts placeholders from the toolbar picker', function () use ($editorHtml) {
    $page = visit(route('quill-browser-test'));

    $page
        ->click('.ql-editor')
        ->click('.ql-picker.ql-placeholders')
        ->click('.ql-picker.ql-placeholders .ql-picker-item[data-value="first_name"]')
        ->assertScript('document.querySelector(".ql-editor").textContent.trim()', '[first_name]')
        ->assertScript($editorHtml, '<p>[first_name]</p>');
})->group('browser');

it('fits the toolbar on a mobile viewport', function () {
    $page = visit(route('quill-browser-test'));

    $page
        ->resize(390, 844)
        ->click('.ql-editor')
        ->typeSlowly('.ql-editor', 'Mobile preview', 10)
        ->assertScript('document.querySelector(".ql-editor").textContent.trim()', 'Mobile preview');
})->group('browser');
