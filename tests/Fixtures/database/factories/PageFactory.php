<?php

declare(strict_types=1);

namespace Rawilk\FilamentQuill\Tests\Fixtures\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rawilk\FilamentQuill\Tests\Fixtures\Models\Page;

final class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => $this->generateContent(),
        ];
    }

    private function generateContent(): string
    {
        $heading = fake()->sentence();
        $content = fake()->paragraph();
        $linkText = fake()->words(4, asText: true);
        $url = fake()->url();

        return <<<HTML
        <h2>{$heading}</h2>
        <p>
            {$content}
            <a href="{$url}">{$linkText}</a>
        </p>
        HTML;
    }
}
