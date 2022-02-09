<?php

namespace Druc\LaravelWire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

class FileCollection extends Collection
{
    public static function create(array $options): self
    {
        Assert::keyExists($options, 'paths');
        Assert::keyExists($options, 'excludedPaths');
        Assert::notEmpty($options['paths']);
        Assert::isArray($options['paths']);
        Assert::isArray($options['excludedPaths']);

        $paths = array_map('base_path', $options['paths']);
        $excludedPaths = array_map('base_path', $options['excludedPaths']);

        $items = new self([]);

        foreach ($paths as $path) {
            if (File::isFile($path)) {
                $items->push(new SplFileInfo($path, $path, $path));
            } else {
                $items->push(...File::allFiles($path));
            }
        }

        return $items->excluding($excludedPaths);
    }

    private function excluding($paths): self
    {
        return $this->filter(function ($item) use ($paths) {
            return !collect($paths)->contains(function ($path) use ($item) {
                return Str::startsWith($item->getRealPath(), $path);
            });
        });
    }
}
