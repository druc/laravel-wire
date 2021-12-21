<?php

namespace Druc\LaravelWire\Http\Controllers;

use Druc\LaravelWire\Exports\DbExport;
use Druc\LaravelWire\Exports\FilesExport;
use Illuminate\Database\ConfigurationUrlParser;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\PostgreSql;
use Spatie\DbDumper\Databases\Sqlite;
use Spatie\DbDumper\DbDumper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WireController
{
    public function __construct()
    {
        if (empty(config('wire.environments.'.config('app.env').'.key')) || request()->header('wire-key') !== config('wire.environments.'.config('app.env').'.key')) {
            abort(403);
        }
    }

    public function database(): BinaryFileResponse
    {
        request()->validate([
            'tables' => ['sometimes', 'array'],
            'excluded_tables' => ['sometimes', 'array'],
        ]);

        $dbExport = new DbExport([
            'db_dumper' => $this->dbDumper(),
            'tables' => request('tables'),
            'excluded_tables' => request('excluded_tables'),
        ]);

        return response()->download($dbExport->path())
            ->deleteFileAfterSend();
    }

    public function files(): BinaryFileResponse
    {
        request()->validate([
            'paths' => ['sometimes', 'array'],
            'excluded_paths' => ['sometimes', 'array'],
        ]);

        $filesExport = new FilesExport([
            'paths' => request('paths'),
            'excluded_paths' => request('excluded_paths'),
        ]);

        return response()
            ->download($filesExport->path())
            ->deleteFileAfterSend();
    }

    private function dbDumper(): DbDumper
    {
        $parser = new ConfigurationUrlParser();
        $connection = config('database.default');
        $config = $parser->parseConfiguration(config("database.connections.$connection"));

        switch ($config['driver']) {
            case 'mysql':
                $dumper = MySql::create();

                break;
            case 'sqlite':
                $dumper = Sqlite::create();

                break;
            case 'pgsql':
                $dumper = PostgreSql::create();

                break;
            default:
                throw new \Exception($config['driver']." not suported");
        }

        return $dumper
            ->setDbName($config['database'])
            ->setUserName($config['username'])
            ->setPassword($config['password']);
    }
}
