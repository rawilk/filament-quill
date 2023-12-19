<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rawilk\FilamentQuill\Tests\Fixtures\Database\Factories\PageFactory;

final class Page extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }
}
