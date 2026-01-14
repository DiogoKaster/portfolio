<?php

namespace Kaster\Cms\Filament\Resources\LinkGroups\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Kaster\Cms\Filament\Resources\LinkGroups\LinkGroupResource;

class EditLinkGroup extends EditRecord
{
    protected static string $resource = LinkGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
