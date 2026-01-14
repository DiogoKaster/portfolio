<?php

namespace Kaster\Cms\Filament\Components;

abstract class AbstractCustomComponent implements ComponentContract
{
    protected static string $view = '';

    public static function getView(): string
    {
        return static::$view;
    }
}
