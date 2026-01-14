<?php

namespace Kaster\Cms\Filament\Components;

use BackedEnum;

interface ComponentContract
{
    /**
     * @return array<int, mixed>
     */
    public static function blockSchema(): array;

    public static function fieldName(): string;

    public static function getGroup(): string|BackedEnum;

    /**
     * @param  array<string, mixed>  $data
     */
    public static function setupRenderPayload(array $data): array;

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string;

    public static function featuredColor(): string;
}
