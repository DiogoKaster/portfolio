<?php

namespace Kaster\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        return [
            //
        ];
    }
}
