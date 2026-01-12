<?php

namespace Kaster\Cms\Filament\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Support\HtmlString;
use Kaster\Cms\Services\ComponentRegistry;

class FilamentComponentsService
{
    public function getFlexibleContentFieldsForModel(): Builder
    {
        $blocks = [];

        $registry = app(ComponentRegistry::class);

        foreach ($registry->all() as $componentClass) {
            $name = sprintf('[%s] %s', $componentClass::getGroup(), str($componentClass::fieldName())->title()->replace('-', ' '));
            $blocks[] =
                Block::make($componentClass::fieldName())
                    ->label(fn(): HtmlString => new HtmlString(sprintf('<span style="display: inline-block; width: 1rem; height: 1rem; background-color: %s; margin-right: 0.5rem; vertical-align: middle;"></span>%s', $componentClass::featuredColor(), $name)))
                    ->schema($componentClass::blockSchema());
        }

        return Builder::make('content')
            ->blockPickerColumns()
            ->blockPickerWidth('2xl')
            ->blocks($blocks)
            ->blockNumbers(false)
            ->addActionLabel(__('Add a component'))
            ->collapsed();
    }
}
