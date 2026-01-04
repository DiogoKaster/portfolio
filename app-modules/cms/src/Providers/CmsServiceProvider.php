<?php

namespace Kaster\Cms\Providers;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
	}
}
