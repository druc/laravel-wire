<?php

namespace Druc\LaravelWire\Http\Controllers;

use Druc\LaravelWire\DbDumper;
use Druc\LaravelWire\Exports\DbExport;
use Druc\LaravelWire\Exports\FilesExport;
use Druc\LaravelWire\FileCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WireController
{
    public function database(): BinaryFileResponse
    {
        request()->validate([
            'tables' => ['sometimes', 'array'],
            'excluded_tables' => ['sometimes', 'array'],
        ]);

        $dbExport = new DbExport([
            'db_dumper' => DbDumper::fromDefault(),
            'tables' => request('tables'),
            'excluded_tables' => request('excluded_tables'),
        ]);

        return response()->download($dbExport->path())
            ->deleteFileAfterSend();
    }

    public function files(): BinaryFileResponse
    {
        request()->validate([
            'file_paths' => ['sometimes', 'array'],
            'excluded_file_paths' => ['sometimes', 'array'],
        ]);

        $filesExport = new FilesExport(
            FileCollection::create([
                'paths' => request('file_paths'),
                'excludedPaths' => request('excluded_file_paths', []),
            ])
        );

        return response()
            ->download($filesExport->path())
            ->deleteFileAfterSend();
    }
}
