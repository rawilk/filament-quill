<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Enums;

enum ToolbarButton: string
{
    case Bold = 'bold';
    case Italic = 'italic';
    case Underline = 'underline';
    case Strike = 'strike';
    case Font = 'font';
    case Size = 'size';
    case BlockQuote = 'blockquote';
    case CodeBlock = 'code-block';
    case OrderedList = 'ordered-list';
    case UnorderedList = 'unordered-list';
    case Indent = 'indent';
    case Scripts = 'scripts';
    case Link = 'link';
    case Image = 'image';
    case TextAlign = 'text-align';
    case TextColor = 'text-color';
    case BackgroundColor = 'background-color';
    case Undo = 'undo';
    case Redo = 'redo';
    case ClearFormat = 'clean';
    case Header = 'header';
}
