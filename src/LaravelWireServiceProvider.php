<?php

namespace Druc\LaravelWire;

use Druc\LaravelWire\Commands\WireDbCommand;
use Druc\LaravelWire\Commands\WireFilesCommand;
use Druc\LaravelWire\Http\Controllers\WireController;
use Druc\LaravelWire\Http\Middleware\Authenticated;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelWireServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-wire')
            ->hasConfigFile()
            ->hasCommand(WireFilesCommand::class)
            ->hasCommand(WireDbCommand::class);
    }

    public function packageRegistered()
    {
        Route::get('/files', [WireController::class, 'files'])->middleware(Authenticated::class);
        Route::get('/database', [WireController::class, 'database'])->middleware(Authenticated::class);
    }
}
