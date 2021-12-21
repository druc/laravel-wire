<?php

namespace Druc\LaravelWire\Commands;

use Druc\LaravelWire\Imports\FilesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Webmozart\Assert\Assert;

class WireFilesCommand extends Command
{
    public $signature = 'wire:files {paths?} {--exclude=}';

    public $description = 'Imports files from environment';

    public function handle(): int
    {
        Assert::notEmpty($this->envUrl(), "Environment url is not accessible: ".$this->envUrl());

        $response = Http::withHeaders([
            'wire-key' => config("wire.environments.".$this->env().".key"),
        ])->get($this->envUrl('/files'), [
            'paths' => array_filter(explode(',', $this->argument('paths'))),
            'excluded_paths' => array_filter(explode(',', $this->option('exclude'))),
        ]);

        if ($response->failed()) {
            $this->error('Request failed with status: '.$response->status());

            return self::FAILURE;
        }

        (new FilesImport($response->body()))->run();

        return self::SUCCESS;
    }

    private function env(): string
    {
        return $this->option('env') ?? config('wire.default', 'stage');
    }

    private function envUrl($segments = ''): string
    {
        return rtrim(config("wire.environments.".$this->env().".url"), '/').$segments;
    }
}
