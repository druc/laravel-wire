<?php

namespace Druc\LaravelWire\Imports;

use Illuminate\Support\Facades\DB;

class MysqlImport
{
    private string $rawSql;

    public function __construct(string $rawSql)
    {
        $this->rawSql = $rawSql;
    }

    public function run()
    {
        DB::unprepared($this->rawSql);
    }
}
