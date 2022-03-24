<?php

namespace Druc\LaravelWire;

use PHPUnit\Framework\Assert;
use Spatie\DbDumper\DbDumper;

class FakeDumper extends DbDumper
{
    private array $includedTables = [];
    private array $excludedTables = [];

    public function includeTables($tables): FakeDumper
    {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
        $this->includedTables = array_merge($this->includedTables, $tables);

        return $this;
    }

    public function excludeTables(array|string $tables): DbDumper
    {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
        $this->excludedTables = array_merge($this->includedTables, $tables);

        return $this;
    }

    public function dumpToFile(string $dumpFile): void
    {
        // TODO: Implement dumpToFile() method.
    }

    public function assertIncludedTables($tables)
    {
        $tables = is_array($tables) ? $tables : explode(',', $tables);

        foreach ($tables as $table) {
            Assert::assertContains($table, $this->includedTables, "Included table missing: $table");
        }
    }

    public function assertExcludedTables($tables)
    {
        $tables = is_array($tables) ? $tables : explode(',', $tables);

        foreach ($tables as $table) {
            Assert::assertContains($table, $this->excludedTables, "Excluded table missing: $table");
        }
    }
}
