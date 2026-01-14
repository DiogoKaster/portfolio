<?php

namespace Kaster\Cms\Filament\Resources\LinkGroups\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Kaster\Cms\Filament\Resources\LinkGroups\LinkGroupResource;

class ListLinkGroups extends ListRecords
{
    protected static string $resource = LinkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }
}
