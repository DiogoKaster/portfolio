<?php

namespace Kaster\Cms\Providers;

use Filament\Panel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Kaster\Cms\CmsPanelPlugin;
use Kaster\Cms\Console\Commands\MakeCmsComponentCommand;
use Kaster\Cms\Filament\Components\ComponentContract;
use Kaster\Cms\Services\ComponentRegistry;
use Symfony\Component\Finder\Finder;

class CmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Panel::configureUsing(static function (Panel $panel): void {
            match ($panel->getId()) {
                'cms' => $panel->plugin(new CmsPanelPlugin()),
                default => null,
            };
        });

        $this->app->singleton(ComponentRegistry::class);
        $this->commands([
            MakeCmsComponentCommand::class,
        ]);
    }

    public function boot(): void
    {
        Blade::componentNamespace('Kaster\\Cms\\View', 'cms');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'cms');
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'cms');

        $this->autoDiscoverComponents();
        $this->registerConfigComponents();
    }

    protected function autoDiscoverComponents(): void
    {
        if (!is_dir(__DIR__ . '/../Filament/Components')) {
            return;
        }

        $registry = $this->app->make(ComponentRegistry::class);
        $finder = Finder::create()->in(__DIR__ . '/../Filament/Components')->files()->name('*.php');

        foreach ($finder as $file) {
            $namespace = 'Kaster\\Cms\\Filament\\Components';
            $relativePath = $file->getRelativePath();

            if ($relativePath) {
                $namespace .= '\\' . str_replace('/', '\\', $relativePath);
            }

            $class = $namespace . '\\' . $file->getBasename('.php');

            if (class_exists($class) && is_subclass_of($class, ComponentContract::class) && !(new \ReflectionClass($class))->isAbstract()) {
                $registry->register($class);
            }
        }
    }

    protected function registerConfigComponents(): void
    {
        $registry = $this->app->make(ComponentRegistry::class);

        foreach (config('cms.components', []) as $componentClass) {
            if (is_string($componentClass) && class_exists($componentClass)) {
                $registry->register($componentClass);
            }
        }
    }
}
