<?php

namespace Kaster\Cms;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Kaster\Cms\Filament\Resources\LinkGroups\LinkGroupResource;
use Kaster\Cms\Filament\Resources\Pages\PageResource;

class CmsPanelPlugin implements Plugin
{
    public function getId(): string
    {
        return 'cms';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            PageResource::class,
            LinkGroupResource::class
        ])->colors([
            'primary' => Color::hex('#559700'),
            ...Color::all()
        ]);
    }

    public function boot(Panel $panel): void {}
}
