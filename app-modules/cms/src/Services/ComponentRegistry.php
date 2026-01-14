<?php

namespace Kaster\Cms\Services;

use Kaster\Cms\Filament\Components\ComponentContract;

class ComponentRegistry
{
    /** @var array<string, class-string<ComponentContract>> */
    protected array $components = [];

    public function register(string $componentClass): void
    {
        if (!is_subclass_of($componentClass, ComponentContract::class)) {
            return;
        }

        $this->components[$componentClass::fieldName()] = $componentClass;
    }

    public function get(string $fieldName): ?string
    {
        return $this->components[$fieldName] ?? null;
    }

    public function all(): array
    {
        return $this->components;
    }
}
