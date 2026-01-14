<?php

namespace Kaster\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kaster\Cms\Enums\LinkItemTarget;
use Kaster\Cms\Models\LinkGroup;
use Kaster\Cms\Models\LinkItem;

class LinkItemFactory extends Factory
{
    protected $model = LinkItem::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->words(2, true),
            'url' => '/' . $this->faker->slug(2),
            'order' => $this->faker->numberBetween(0, 100),
            'target' => LinkItemTarget::Self,
        ];
    }

    public function forLinkGroup(LinkGroup $linkGroup): static
    {
        return $this->state(['link_group_id' => $linkGroup->id]);
    }

    public function withParent(LinkItem $parent): static
    {
        return $this->state([
            'parent_id' => $parent->id,
            'link_group_id' => $parent->link_group_id,
        ]);
    }

    public function externalLink(?string $url = null): static
    {
        return $this->state([
            'url' => $url ?? $this->faker->url(),
            'target' => LinkItemTarget::Blank,
        ]);
    }
}
