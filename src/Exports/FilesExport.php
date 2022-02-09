<?php

namespace Druc\LaravelWire\Exports;

use Druc\LaravelWire\FileCollection;
use Druc\LaravelWire\NameOfFileInZip;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ZipArchive;

class FilesExport
{
    private Collection $files;
    private ?string $zipPath;

    public function __construct(FileCollection $files, ?string $zipPath = null)
    {
        $this->files = $files;
        $this->zipPath = $zipPath ?? storage_path('wire/wire.zip');
    }

    public function path(): string
    {
        File::ensureDirectoryExists(dirname($this->zipPath));
        
        // creates empty zip
        File::put($this->zipPath, base64_decode("UEsFBgAAAAAAAAAAAAAAAAAAAAAAAA=="));

        $zip = new ZipArchive();
        $zip->open($this->zipPath, ZipArchive::OVERWRITE);

        foreach ($this->files as $file) {
            $zip->addFile(
                $file->getPathname(),
                (string) new NameOfFileInZip($file->getPathname(), $this->zipPath)
            );
        }

        $zip->close();

        return $this->zipPath;
    }
}
