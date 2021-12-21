<?php

namespace Druc\LaravelWire\Tests;

use Druc\LaravelWire\Imports\FilesImport;
use Illuminate\Support\Facades\File;
use ZipArchive;

class FilesImportTest extends TestCase
{
    /** @test */
    public function import_files()
    {
        $zipPath = base_path('my-archive.zip');
        $fileName = 'test.txt';
        $filePath = base_path($fileName);

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        $zip->addFromString($fileName, 'This is just a test file');
        $zip->close();

        $this->assertFileDoesNotExist($filePath);

        (new FilesImport(File::get($zipPath)))->run();

        $this->assertFileExists($filePath);

        File::delete([$filePath, $zipPath]);
    }
}
