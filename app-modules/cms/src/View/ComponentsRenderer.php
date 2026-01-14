<?php

namespace Kaster\Cms\View;

use Illuminate\View\Component;
use Illuminate\View\View;
use Kaster\Cms\Services\ComponentRegistry;

class ComponentsRenderer extends Component
{
    public function __construct(
        public array $block
    ) {
    }

    public function render(): View|string
    {
        $type = $this->block['type'] ?? null;

        $componentClass = app(ComponentRegistry::class)->get($type);

        if (!$componentClass) {
            return '';
        }

        $viewName = $componentClass::getView();

        $payload = $componentClass::setupRenderPayload($this->block['data'] ?? []);

        return view($viewName, $payload);
    }
}
