<?php

namespace Kaster\Cms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeCmsComponentCommand extends Command
{
    protected $signature = 'cms:make-component {name : The name of the component class (e.g. Heroes/HeroBlock)}';

    protected $description = 'Create a new CMS component and its view';

    public function handle(Filesystem $files): int
    {
        $name = $this->argument('name');

        // Normalize name, e.g. Heroes/HeroBlock or HeroBlock
        $className = class_basename($name);
        // If there's a subdirectory, get it.
        // We assume Input is like "Heroes/HeroBlock" -> Namespace Kaster\Cms\Filament\Components\Heroes

        $pathParts = explode('/', str_replace('\\', '/', $name));
        $className = array_pop($pathParts);
        $subNamespace = implode('\\', $pathParts);

        $namespace = 'Kaster\\Cms\\Filament\\Components';
        if ($subNamespace) {
            $namespace .= '\\' . $subNamespace;
        }

        $directory = base_path('app-modules/cms/src/Filament/Components/' . implode('/', $pathParts));
        $path = $directory . '/' . $className . '.php';

        if ($files->exists($path)) {
            $this->error("Component {$className} already exists!");
            return self::FAILURE;
        }

        $files->ensureDirectoryExists($directory);

        // View name generation
        // If className is "HeroBlock", view might be "hero-block"
        // If subNamespace is "Heroes", view might be "heroes.hero-block"

        $kebabName = Str::kebab($className);
        $viewDirectoryPath = '';
        if ($subNamespace) {
            $viewDirectoryPath = strtolower(str_replace('\\', '.', $subNamespace)) . '.';
        }
        $dotViewName = 'cms::components.' . $viewDirectoryPath . $kebabName;

        // Component Stub
        $stub = <<<PHP
<?php

namespace {$namespace};

use Filament\Forms\Components\TextInput;
use Kaster\Cms\Filament\Components\AbstractCustomComponent;

class {$className} extends AbstractCustomComponent
{
    protected static string \$view = '{$dotViewName}';

    public static function blockSchema(): array
    {
        return [
            TextInput::make('text'),
        ];
    }

    public static function fieldName(): string
    {
        return '{$kebabName}';
    }

    public static function getGroup(): string
    {
        return 'Blocks';
    }

    public static function setupRenderPayload(array \$data): array
    {
        return [
            'text' => \$data['text'],
        ];
    }

    public static function toSearchableContent(array \$data): string
    {
        return '';
    }

    public static function featuredColor(): string
    {
        return 'gray';
    }
}
PHP;

        $files->put($path, $stub);
        $this->info("Component class created: {$path}");

        // Create View
        $viewPathBase = base_path('app-modules/cms/resources/views/components/');
        $viewSubDir = str_replace('.', '/', $viewDirectoryPath);
        $viewFile = $viewPathBase . $viewSubDir . $kebabName . '.blade.php';

        $files->ensureDirectoryExists(dirname($viewFile));

        $viewStub = <<<HTML
<div>
    <!-- {$className} Component -->
    {{ \$text ?? '' }}
</div>
HTML;

        if (!$files->exists($viewFile)) {
            $files->put($viewFile, $viewStub);
            $this->info("View file created: {$viewFile}");
        } else {
            $this->warn("View file already exists: {$viewFile}");
        }

        return self::SUCCESS;
    }
}
