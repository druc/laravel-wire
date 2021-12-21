<?php

namespace Druc\LaravelWire\Tests;

use Druc\LaravelWire\Exports\DbExport;
use Druc\LaravelWire\FakeDumper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\DbDumper\Databases\Sqlite;

class DbExportTest extends TestCase
{
    /** @test */
    public function export_includes_tables()
    {
        $dumper = new FakeDumper();

        (new DbExport([
            'db_dumper' => $dumper,
            'tables' => ['users', 'orders'],
        ]))->path();

        $dumper->assertIncludedTables(['users', 'orders']);
    }

    /** @test */
    public function export_excludes_tables()
    {
        $dumper = new FakeDumper();

        (new DbExport([
            'db_dumper' => $dumper,
            'excluded_tables' => ['users', 'orders'],
        ]))->path();

        $dumper->assertExcludedTables(['users', 'orders']);
    }
}
