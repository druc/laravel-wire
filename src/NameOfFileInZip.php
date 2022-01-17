<?php

namespace Druc\LaravelWire;

use Illuminate\Support\Str;

class NameOfFileInZip
{
    private string $pathToFile;
    private string $pathToZip;
    private string $relativePath;

    public function __construct(
        string $pathToFile,
        string $pathToZip,
        string $relativePath
    ) {
        $this->pathToFile = $pathToFile;
        $this->pathToZip = $pathToZip;
        $this->relativePath = $relativePath;
    }

    public function __toString()
    {
        $fileDirectory = pathinfo($this->pathToFile, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR;
        $zipDirectory = pathinfo($this->pathToZip, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR;

        if (Str::startsWith($fileDirectory, $zipDirectory)) {
            return str_replace($zipDirectory, '', $this->pathToFile);
        }

        if ($this->relativePath && $this->relativePath !== DIRECTORY_SEPARATOR && Str::startsWith($fileDirectory, $this->relativePath)) {
            return str_replace($this->relativePath, '', $this->pathToFile);
        }

        return $this->pathToFile;
    }
}
