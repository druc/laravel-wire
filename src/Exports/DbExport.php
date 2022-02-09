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
        $this->exportPath = $params['exportPath'] ?? storage_path('wire.sql');
    }

    public function path(): string
    {
        if (count($this->tables)) {
            $this->dbDumper->includeTables($this->tables);
        }

        if (count($this->excludedTables)) {
            $this->dbDumper->excludeTables($this->excludedTables);
        }

        $this->dbDumper->dumpToFile($this->exportPath);

        return $this->exportPath;
    }
}
