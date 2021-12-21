<?php

namespace Druc\LaravelWire\Tests;

use Illuminate\Support\Facades\Http;

class WireFilesCommandTest extends TestCase
{
    /** @test */
    public function fails_when_request_is_not_successfull()
    {
        Http::fake([
            config('wire.environments.stage.url').'/files' => Http::response([], 404),
        ]);

        $this->artisan("wire:files")->assertExitCode(1);
    }
}
