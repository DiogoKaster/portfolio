<?php

namespace Kaster\Cms\Enums;

use Filament\Support\Contracts\HasLabel;

enum LinkItemTarget: string implements HasLabel
{
    case Self = 'self';
    case Blank = 'blank';

    public function getLabel(): string
    {
        return match ($this) {
            self::Self => __('Same window'),
            self::Blank => __('New window'),
        };
    }

    public function toHtmlAttribute(): string
    {
        return match ($this) {
            self::Self => '_self',
            self::Blank => '_blank',
        };
    }
}
