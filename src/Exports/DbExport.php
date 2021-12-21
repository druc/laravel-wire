<?php

namespace Druc\LaravelWire\Exports;

use Spatie\DbDumper\DbDumper;
use Webmozart\Assert\Assert;

class DbExport
{
    private DbDumper $dbDumper;
    private array $tables;
    private array $excludedTables;
    private string $exportPath;

    public function __construct(array $params = [])
    {
        Assert::isInstanceOf($params['db_dumper'], DbDumper::class);
        $this->dbDumper = $params['db_dumper'];
        $this->tables = $params['tables'] ?? [];
        $this->excludedTables = $params['excluded_tables'] ?? [];
        $this->exportPath = $params['export_path'] ?? storage_path('wire.sql');
    }

    public function path(): string
    {
        if (count($this->tables())) {
            $this->dbDumper->includeTables($this->tables());
        }

        $this->dbDumper
            ->excludeTables($this->excludedTables())
            ->dumpToFile($this->exportPath());

        return $this->exportPath();
    }

    private function exportPath(): string
    {
        return $this->exportPath;
    }

    private function excludedTables()
    {
        return $this->excludedTables;
    }

    private function tables()
    {
        return $this->tables;
    }
}
