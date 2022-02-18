<?php

namespace Druc\LaravelWire\Commands;

use Druc\LaravelWire\EnvironmentConfig;
use Druc\LaravelWire\Imports\FilesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class WireFilesCommand extends Command
{
    public $signature = 'wire:files {environment?} {--p|paths=} {--e|exclude=}';

    public $description = 'Imports files from environment';

    public function handle(): int
    {
        $response = Http::withHeaders([
            'wire-key' => $this->config()->authKey(),
        ])->withBasicAuth(
            $this->config()->basicAuthUsername(),
            $this->config()->basicAuthPassword()
        )->get($this->config()->url('/files'), [
            'file_paths' => $this->filePaths(),
            'excluded_file_paths' => $this->excludedFilePaths(),
        ]);

        if ($response->failed()) {
            $this->error('Request failed with status: '.$response->status());

            return self::FAILURE;
        }

        (new FilesImport($response->body()))->run();

        return self::SUCCESS;
    }

    private function config(): EnvironmentConfig
    {
        return new EnvironmentConfig($this->argument('environment'));
    }

    private function filePaths(): array
    {
        if ($this->option('paths') !== null) {
            return array_filter(explode(',', $this->option('paths')));
        }

        return $this->config()->filePaths();
    }

    private function excludedFilePaths(): array
    {
        if ($this->option('exclude') !== null) {
            return array_filter(explode(',', $this->option('exclude')));
        }

        return $this->config()->excludedFilePaths();
    }
}
