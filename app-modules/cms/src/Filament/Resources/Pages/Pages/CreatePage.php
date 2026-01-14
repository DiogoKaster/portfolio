<?php

namespace Kaster\Cms\Filament\Resources\Pages\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Kaster\Cms\Filament\Resources\Pages\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    public bool $isJsonVisible = false;

    protected Width|string|null $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('json')
                ->action(fn (): bool => $this->isJsonVisible = ! $this->isJsonVisible)
                ->outlined(fn (): bool => $this->isJsonVisible)
                ->label('Toggle Json'),
        ];
    }
}
