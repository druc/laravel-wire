<?php

namespace Druc\LaravelWire;

use Illuminate\Database\ConfigurationUrlParser;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\PostgreSql;
use Spatie\DbDumper\Databases\Sqlite;

class DbDumper
{
    public static function fromDefault(): \Spatie\DbDumper\DbDumper
    {
        $parser = new ConfigurationUrlParser();
        $connection = config('database.default');
        $config = $parser->parseConfiguration(config("database.connections.$connection"));

        $drivers = [
            'mysql' => MySql::class,
            'sqlite' => Sqlite::class,
            'pgsql' => PostgreSql::class,
        ];

        if (!isset($drivers[$config['driver']])) {
            throw new \Exception($config['driver']." not suported");
        }

        return $drivers[$config['driver']]::create()
            ->setDbName($config['database'])
            ->setUserName($config['username'])
            ->setPassword($config['password']);
    }
}
