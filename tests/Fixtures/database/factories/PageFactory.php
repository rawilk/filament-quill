<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Database\Factories;

use Awcodes\HtmlFaker\HtmlFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Rawilk\FilamentQuill\Tests\Fixtures\Models\Page;

final class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $content = HtmlFaker::make()
            ->heading()
            ->paragraphs(withRandomLinks: true)
            ->generate();

        return [
            'title' => fake()->sentence(),
            'content' => $content,
        ];
    }
}
