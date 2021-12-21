<?php

namespace Druc\LaravelWire\Tests;

use Druc\LaravelWire\Exports\FilesExport;
use Illuminate\Support\Facades\File;
use ZipArchive;

class FilesExportTest extends TestCase
{
    /** @test */
    public function export_path_for_file_path()
    {
        File::put(storage_path('temp.txt'), 'temporary file');

        $export = new FilesExport([
            'paths' => [$this->path('storage/temp.txt')],
            'zip_path' => storage_path('wire/wire.zip'),
        ]);

        $zip = new ZipArchive();
        $zip->open($export->path());
        $this->assertIsInt($zip->locateName($this->path('/storage/temp.txt')));

        File::delete(storage_path('temp.txt'));
    }

    /** @test */
    public function export_path_for_directory_path()
    {
        File::ensureDirectoryExists(storage_path('files'));
        File::put(storage_path('files/temp.txt'), 'temporary file');

        $export = new FilesExport([
            'paths' => [$this->path('storage/files')],
            'zip_path' => storage_path('wire/wire.zip'),
        ]);

        $zip = new ZipArchive();
        $zip->open($export->path());
        $this->assertIsInt($zip->locateName($this->path('/storage/files/temp.txt')));

        File::deleteDirectory(storage_path('files'));
    }

    /** @test */
    public function export_path_with_excluded_file_paths()
    {
        File::put(storage_path('temp.txt'), 'temporary file');
        File::put(storage_path('excluded_temp.txt'), 'Excluded temporary file');

        $export = new FilesExport([
            'paths' => [$this->path('storage/temp.txt')],
            'excluded_paths' => [$this->path('storage/excluded_temp.txt')],
            'zip_path' => storage_path('wire/wire.zip'),
        ]);

        $zip = new ZipArchive();
        $zip->open($export->path());
        $this->assertIsInt($zip->locateName($this->path('/storage/temp.txt')));
        $this->assertFalse($zip->locateName($this->path('/storage/excluded_temp.txt')));

        File::delete([storage_path('temp.txt'), storage_path('excluded_temp.txt')]);
    }

    /** @test */
    public function export_path_with_excluded_directory_paths()
    {
        File::ensureDirectoryExists(storage_path('files'));
        File::put(storage_path('temp.txt'), 'temporary file');
        File::put(storage_path('files/excluded_temp.txt'), 'Excluded temporary file');

        $export = new FilesExport([
            'paths' => [$this->path('storage/temp.txt')],
            'excluded_paths' => [$this->path('storage/files')],
            'zip_path' => storage_path('wire/wire.zip'),
        ]);

        $zip = new ZipArchive();
        $zip->open($export->path());
        $this->assertIsInt($zip->locateName($this->path('/storage/temp.txt')));
        $this->assertFalse($zip->locateName($this->path('/storage/files/excluded_temp.txt')));

        File::deleteDirectory(storage_path('files'));
        File::delete([storage_path('temp.txt')]);
    }

    /** @test */
    public function export_path_with_no_files()
    {
        $export = new FilesExport([
            'paths' => [],
            'zip_path' => storage_path('wire/wire.zip'),
        ]);
        $this->assertFileExists($export->path());
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('wire'));
        parent::tearDown();
    }

    private function path(string $path)
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}
