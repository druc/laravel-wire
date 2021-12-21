<?php

namespace Druc\LaravelWire\Tests;

use Druc\LaravelWire\Imports\MysqlImport;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class MysqlImportTest extends TestCase
{
    /** @test */
    public function import_mysql()
    {
        try {
            (new MysqlImport('CREATE TABLE groups (group_id INTEGER PRIMARY KEY)'))->run();
            DB::table('groups')->count();
        } catch (QueryException $e) {
            $this->fail('Failed to import mysql.');
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function throws_exception_when_raw_sql_string_is_invalid()
    {
        $this->expectException(QueryException::class);
        (new MysqlImport('Some invalid mysql statement'))->run();
    }
}
