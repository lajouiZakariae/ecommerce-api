<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make:repo {repoClass}', function (string $repoClass) {
    dump($repoClass);

    $dir_path = str(app_path('Repositories'));

    $file_path = $dir_path
        ->append('/')
        ->append($repoClass)
        ->append('.php');

    $content = str("<?php")
        ->newLine(2)
        ->append("namespace App\Repositories;")
        ->newLine(2)
        ->append("class $repoClass {")
        ->newLine(2)
        ->append("}");

    if (!File::isDirectory($dir_path)) File::makeDirectory($dir_path);

    File::put($file_path, $content);
});

Artisan::command('make:service {serviceClass}', function (string $serviceClass) {
    dump($serviceClass);

    $dir_path = str(app_path('Services'));

    $file_path = $dir_path
        ->append('/')
        ->append($serviceClass)
        ->append('.php');

    $content = str("<?php")
        ->newLine(2)
        ->append("namespace App\Services;")
        ->newLine(2)
        ->append("class $serviceClass {")
        ->newLine(2)
        ->append("}");

    if (!File::isDirectory($dir_path)) File::makeDirectory($dir_path);

    File::put($file_path, $content);
});
