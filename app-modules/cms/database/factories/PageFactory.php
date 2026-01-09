<?php

namespace Kaster\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Kaster\Cms\Enums\PageStatus;
use Kaster\Cms\Enums\PageTheme;
use Kaster\Cms\Models\Page;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'lang' => 'pt_BR',
            'status' => PageStatus::PUBLISHED,
            'content' => [],
            'seo_metadata' => [],
            'is_landing' => $this->faker->boolean(),
            'theme' => PageTheme::Default ,
            'published_at' => now(),
        ];
    }
}
