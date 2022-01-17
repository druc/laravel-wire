<?php

namespace Druc\LaravelWire\Exports;

use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\DbDumper;
use Webmozart\Assert\Assert;

class DbExport
{
    private DbDumper $dbDumper;
    private array $tables;
    private array $excludedTables;

    public function __construct(array $params = [])
    {
        Assert::isInstanceOf($params['db_dumper'], DbDumper::class);
        $this->dbDumper = $params['db_dumper'];
        $this->tables = $params['tables'] ?? [];
        $this->excludedTables = $params['excluded_tables'] ?? [];
    }

    public function path(): string
    {
        if (count($this->tables())) {
            $this->dbDumper->includeTables($this->tables());
        }

        if ($this->dbDumper instanceof MySql) {
            $this->dbDumper->doNotUseColumnStatistics();
        }

        $this->dbDumper
            ->excludeTables($this->excludedTables())
            ->dumpToFile($this->exportPath());

        return $this->exportPath();
    }

    private function tables()
    {
        return $this->tables;
    }

    private function excludedTables()
    {
        return $this->excludedTables;
    }

    private function exportPath(): string
    {
        return storage_path('wire.sql');
    }
}
