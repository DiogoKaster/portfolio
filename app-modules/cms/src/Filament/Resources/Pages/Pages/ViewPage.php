<?php

namespace Kaster\Cms\Filament\Resources\Pages\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Kaster\Cms\Filament\Resources\Pages\PageResource;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    public bool $isJsonVisible = false;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
