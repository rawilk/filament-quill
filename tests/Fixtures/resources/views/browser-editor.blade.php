<!DOCTYPE html>
<html lang="en" class="fi">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quill Browser Test</title>

    @filamentStyles
    {{ filament()->getTheme()->getHtml() }}

    <style>
        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';
            --mono-font-family: '{!! filament()->getMonoFontFamily() !!}';
            --serif-font-family: '{!! filament()->getSerifFontFamily() !!}';
            --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
        }
    </style>
</head>
<body class="fi-body fi-panel-admin min-h-screen bg-gray-50 font-sans text-gray-950 antialiased">
    <main class="mx-auto flex min-h-screen w-full max-w-4xl items-center px-4 py-10">
        <livewire:editor-form />
    </main>

    @filamentScripts(withCore: true)
</body>
</html>
