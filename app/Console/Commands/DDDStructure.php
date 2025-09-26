<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DDDStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ddd {path : The path} {entity : The entity to create the DDD structure, books for example}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates DDD folder structure for the given entity';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->argument('path');
        $entity = $this->argument('entity');

        $base_path="src/";

        // $path_namespace = str_replace('/', '\\', trim($path, '/'));;

        $uri = base_path($base_path. $path);
        $this->info('Creating structure...');

        File::makeDirectory($uri . '/domain', 0755, true, true);
        $this->info($uri . '/domain');

        File::makeDirectory($uri . '/domain/entities', 0755, true, true);
        $this->info($uri . '/domain/entities');

        File::makeDirectory($uri . '/domain/value_objects', 0755, true, true);
        $this->info($uri . '/domain/value_objects');

        File::makeDirectory($uri . '/domain/contracts', 0755, true, true);
        $this->info($uri . '/domain/contracts');

        File::makeDirectory($uri . '/application/contracts', 0755, true, true);
        $this->info($uri . '/application/contracts');

        File::makeDirectory($uri . '/application/contracts/out', 0755, true, true);
        $this->info($uri . '/application/contracts/out');

               File::makeDirectory($uri . '/domain/contracts/in', 0755, true, true);
        $this->info($uri . '/application/contracts/in');

        File::makeDirectory($uri . '/application', 0755, true, true);
        $this->info($uri . '/application');

        File::makeDirectory($uri . '/application/DTOs', 0755, true, true);
        $this->info($uri . '/application/DTOs');

        File::makeDirectory($uri . '/application/use_cases', 0755, true, true);
        $this->info($uri . '/application/use_cases');

        File::makeDirectory($uri . '/infrastructure', 0755, true, true);
        $this->info($uri . '/infrastructure');

        File::makeDirectory($uri . '/infrastructure/controllers', 0755, true, true);
        $this->info($uri . '/infrastructure/controllers');

        File::makeDirectory($uri . '/infrastructure/routes', 0755, true, true);
        $this->info($uri . '/infrastructure/routes');

        File::makeDirectory($uri . '/infrastructure/validators', 0755, true, true);
        $this->info($uri . '/infrastructure/validators');

        File::makeDirectory($uri . '/infrastructure/repositories', 0755, true, true);
        $this->info($uri . '/infrastructure/repositories');

        File::makeDirectory($uri . '/infrastructure/providers', 0755, true, true);
        $this->info($uri . '/infrastructure/providers');

        if($entity !== 'SharedModule') {
            // api.php
            $content = <<<PHP
            <?php

            use Illuminate\Support\Facades\Route;

            PHP;

            File::put($uri . '/infrastructure/routes/api.php', $content);
            $this->info('Routes entry point added in ' . $uri . '/infrastructure/routes/api.php' );
            // local api.php added to main api.php
            $content = <<<PHP
            Route::prefix("$path")->group(base_path('{$base_path}/{$path}/infrastructure/routes/api.php'));
            PHP;

            File::append(base_path('routes/api.php'), $content);
            $this->info('Module routes linked in main routes directory.');

            // add entity

            $content = <<<PHP
            <?php

            namespace {ucfist($base_path)}\$path_namespace\\domain\\entities;

            class {$entity}
            {
                public function __construct() {}
            }
            PHP;

            File::put($uri . "/domain/entities/{$entity}.php", $content);
            $this->info('Entity added');

            // ValidatorRequest.php
            $content = <<<PHP
            <?php

            namespace {ucfist($base_path)}\$path_namespace\\infrastructure\\validators;

            use Illuminate\Foundation\Http\FormRequest;

            class ExampleValidatorRequest extends FormRequest{

                public function authorize() {
                    return true;
                }

                public function rules(){
                    return [
                        "field" => 'nullable|max:255'
                    ];
                }
            }
            PHP;

            File::put($uri.'/infrastructure/validators/ExampleValidatorRequest.php', $content);
            $this->info('Example validation request added');

            // example controller

            $content = <<<PHP
            <?php

            namespace {ucfist($base_path)}\$path_namespace\\infrastructure\\controllers;

            use App\Http\Controllers\Controller;

            class {$entity}Controller extends Controller {
                //
            }
            PHP;

            File::put($uri.'/infrastructure/controllers/'.$entity.'Controller.php', $content);
            $this->info('Example controller added');

            $content = <<<PHP
            <?php

            namespace Src\\product\\infrastructure\\providers;

            use Illuminate\\Support\\ServiceProvider;

            class {$entity}ServiceProvider extends ServiceProvider
            {
                public function register()
                {
                    //
                }

                public function boot()
                {
                    //
                }
            }
            PHP;

            File::put($uri.'/infrastructure/providers/'.$entity.'ServiceProvider.php', $content);
            $this->info('Example provider added');

            $this->info('Structure ' . $entity . ' DDD successfully created.');
        }
        return Command::SUCCESS;
    }
}
