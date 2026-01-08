<?php

namespace Kaster\Cms\Filament\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Support\HtmlString;
//use Webmozart\Assert\Assert;

class FilamentComponentsService
{
    public function getFlexibleContentFieldsForModel(string $modelClassName): Builder
    {
        $blocks = [];

        $registry = app(\Kaster\Cms\Services\ComponentRegistry::class);

        foreach ($registry->all() as $componentClass) {
            /** @var ComponentContract $componentClass */
            // logic for disabled_for is removed as it was not present in the Enum implementation
            // If needed, it should be added to ComponentContract

            $name = sprintf('[%s] %s', $componentClass::getGroup(), str($componentClass::fieldName())->title()->replace('-', ' '));
            $blocks[] =
                Block::make($componentClass::fieldName())
                    ->label(fn(): HtmlString => new HtmlString(sprintf('<span style="display: inline-block; width: 1rem; height: 1rem; background-color: %s; margin-right: 0.5rem; vertical-align: middle;"></span>%s', $componentClass::featuredColor(), $name)))
                    ->schema($componentClass::blockSchema());
        }

        return Builder::make('content')
            ->blockPickerColumns(2)
            ->blockPickerWidth('2xl')
            ->blocks($blocks)
            ->blockNumbers(false)
            ->addActionLabel(__('Add a component'))
            ->collapsed();
    }
}
