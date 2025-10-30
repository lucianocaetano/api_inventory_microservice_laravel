<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DDDStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ddd {path : The path (e.g. product)} {entity : The entity (e.g. Book)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates DDD folder structure for the given entity';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path   = Str::lower($this->argument('path'));
        $entity = Str::studly($this->argument('entity'));

        $basePath   = "src/{$path}";
        $modulePath = base_path($basePath);

        $this->info("📂 Creando estructura para {$entity} en {$modulePath}");

        $this->makeDir("$modulePath/domain/entities");
        $this->makeDir("$modulePath/domain/value_objects");
        $this->makeDir("$modulePath/domain/contracts");

        $this->makeDir("$modulePath/application/contracts/in");
        $this->makeDir("$modulePath/application/contracts/out");

        $this->makeDir("$modulePath/application/DTOs");
        $this->makeDir("$modulePath/application/use_cases");

        $this->makeDir("$modulePath/infrastructure/controllers");
        $this->makeDir("$modulePath/infrastructure/routes");
        $this->makeDir("$modulePath/infrastructure/validators");
        $this->makeDir("$modulePath/infrastructure/repositories");
        $this->makeDir("$modulePath/infrastructure/providers");

        if ($entity !== 'SharedModule') {
            $routesContent = <<<PHP
            <?php

            use Illuminate\Support\Facades\Route;

            PHP;

            File::put("$modulePath/infrastructure/routes/api.php", $routesContent);
            $this->info("✅ Routes entry point creado en {$basePath}/infrastructure/routes/api.php");

            $routeInclude = <<<PHP
            Route::prefix('$path')->group(base_path('{$basePath}/infrastructure/routes/api.php'));
            PHP;

            $apiFile = base_path('routes/api.php');
            if (!str_contains(File::get($apiFile), $routeInclude)) {
                File::append($apiFile, PHP_EOL . $routeInclude . PHP_EOL);
                $this->info("✅ Módulo $path vinculado en routes/api.php");
            } else {
                $this->warn("⚠️ Ya existía la referencia de rutas en routes/api.php");
            }

            $entityContent = <<<PHP
            <?php

            namespace Src\\$path\\domain\\entities;

            class {$entity}
            {
                public function __construct() {}
            }
            PHP;

            File::put("$modulePath/domain/entities/{$entity}.php", $entityContent);
            $this->info("✅ Entity {$entity} creada");

            $validatorContent = <<<PHP
            <?php

            namespace Src\\$path\\infrastructure\\validators;

            use Illuminate\Foundation\Http\FormRequest;

            class ExampleValidatorRequest extends FormRequest
            {
                public function authorize(): bool
                {
                    return true;
                }

                public function rules(): array
                {
                    return [
                        'field' => 'nullable|max:255',
                    ];
                }
            }
            PHP;

            File::put("$modulePath/infrastructure/validators/ExampleValidatorRequest.php", $validatorContent);
            $this->info("✅ ExampleValidatorRequest creado");

            $controllerContent = <<<PHP
            <?php

            namespace Src\\$path\\infrastructure\\controllers;

            use App\Http\Controllers\Controller;

            class {$entity}Controller extends Controller
            {
                //
            }
            PHP;

            File::put("$modulePath/infrastructure/controllers/{$entity}Controller.php", $controllerContent);
            $this->info("✅ {$entity}Controller creado");

            $providerContent = <<<PHP
            <?php

            namespace Src\\$path\\infrastructure\\providers;

            use Illuminate\\Support\\ServiceProvider;

            class {$entity}ServiceProvider extends ServiceProvider
            {
                public function register(): void
                {
                    //
                }

                public function boot(): void
                {
                    //
                }
            }
            PHP;

            File::put("$modulePath/infrastructure/providers/{$entity}ServiceProvider.php", $providerContent);
            $this->info("✅ {$entity}ServiceProvider creado");
        }

        $this->info("🎉 Estructura DDD de {$entity} generada con éxito.");
        return Command::SUCCESS;
    }

    /**
     * Helper para crear directorios
     */
    private function makeDir(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
            $this->info("📁 Directorio creado: $path");
        } else {
            $this->warn("⚠️ Directorio ya existe: $path");
        }
    }
}

