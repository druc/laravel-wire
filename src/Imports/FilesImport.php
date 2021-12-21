<?php

namespace Druc\LaravelWire\Imports;

use Illuminate\Support\Facades\File;
use ZipArchive;

class FilesImport
{
    private string $contents;
    private string $tempPath;

    public function __construct(string $contents, string $tempPath = null)
    {
        $this->contents = $contents;
        $this->tempPath = $tempPath ?? base_path('wire.zip');
    }

    public function run()
    {
        // Create zip from response body
        File::put($this->tempPath, $this->contents);

        // Extract, then delete zip file
        $zip = new ZipArchive();
        $res = $zip->open($this->tempPath);

        if ($res === true) {
            $zip->extractTo(base_path());
            $zip->close();
            File::delete($this->tempPath);
        }
    }
}
