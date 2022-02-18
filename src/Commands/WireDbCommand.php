<?php

namespace Druc\LaravelWire\Commands;

use Druc\LaravelWire\EnvironmentConfig;
use Druc\LaravelWire\Imports\MysqlImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class WireDbCommand extends Command
{
    public $signature = 'wire:db {environment?} {--t|tables=} {--e|exclude=}';

    public $description = 'Imports database tables from environment';

    public function handle(): int
    {
        if ($this->option('tables') && $this->option('exclude')) {
            $this->error('Cannot exclude and include tables at the same time.');

            return self::FAILURE;
        }

        $response = Http::withHeaders([
            'wire-key' => $this->config()->authKey(),
        ])->withBasicAuth(
            $this->config()->basicAuthUsername(),
            $this->config()->basicAuthPassword()
        )->get($this->config()->url('/database'), [
            'tables' => $this->tables(),
            'excluded_tables' => $this->excludedTables(),
        ]);

        if ($response->failed()) {
            $this->error('Request failed with status: '.$response->status());

            return self::FAILURE;
        }

        (new MysqlImport($response->body()))->run();

        return self::SUCCESS;
    }

    private function config(): EnvironmentConfig
    {
        return new EnvironmentConfig($this->argument('environment'));
    }

    private function tables(): array
    {
        return array_filter(explode(',', $this->option('tables')));
    }

    private function excludedTables(): array
    {
        return array_filter(explode(',', $this->option('exclude')));
    }
}
