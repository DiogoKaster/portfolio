<?php

namespace Kaster\Cms\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuItemType: string implements HasLabel
{
    case Resource = 'resource';
    case Custom = 'custom';
    case Dropdown = 'dropdown';

    public function getLabel(): string
    {
        return match ($this) {
            self::Resource => __('Link to a resource'),
            self::Custom => __('Custom URL'),
            self::Dropdown => __('Dropdown (no link)'),
        };
    }

    public function requiresUrl(): bool
    {
        return $this === self::Custom;
    }

    public function requiresModel(): bool
    {
        return $this === self::Resource;
    }

    public function isLinkable(): bool
    {
        return $this !== self::Dropdown;
    }
}
