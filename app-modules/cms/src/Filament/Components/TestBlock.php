<?php

namespace Kaster\Cms\Filament\Components;

use Filament\Forms\Components\TextInput;
use Kaster\Cms\Filament\Components\AbstractCustomComponent;

class TestBlock extends AbstractCustomComponent
{
    protected static string $view = 'cms::components.test-block';

    public static function blockSchema(): array
    {
        return [
            TextInput::make('text'),
        ];
    }

    public static function fieldName(): string
    {
        return 'test-block';
    }

    public static function getGroup(): string
    {
        return 'Blocks';
    }

    public static function setupRenderPayload(array $data): array
    {
        return [
            'text' => $data['text'],
        ];
    }

    public static function toSearchableContent(array $data): string
    {
        return '';
    }

    public static function featuredColor(): string
    {
        return 'gray';
    }
}