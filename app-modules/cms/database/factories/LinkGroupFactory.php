<?php

namespace Kaster\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Kaster\Cms\Models\LinkGroup;

class LinkGroupFactory extends Factory
{
    protected $model = LinkGroup::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(2, true),
            'position' => $this->faker->randomElement(['header', 'footer', 'sidebar']),
            'lang' => 'en',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $linkGroup): void {
            /** @var LinkGroup $linkGroup */
            if ($linkGroup->translation_origin_id) {
                return;
            }

            $linkGroup->update(['translation_origin_id' => $linkGroup->getKey()]);
        });
    }

    public function asATranslationFrom(LinkGroup $linkGroup, string $lang): static
    {
        return $this->state(function (array $attributes) use ($lang, $linkGroup): array {
            return [
                'lang' => $lang,
                'translation_origin_id' => $linkGroup->getKey(),
            ];
        });
    }

    public function position(string $position): static
    {
        return $this->state(['position' => $position]);
    }
}
