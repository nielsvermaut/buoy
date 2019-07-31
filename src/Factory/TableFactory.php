<?php

namespace Buoy\Factory;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

class TableFactory
{
    public const DATABASES_TABLE_NAME = 'buoy_databases';

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function createBuoyDatabasesTable(): Table
    {
        return new Table(
            self::DATABASES_TABLE_NAME,
            [
                new Column('name', Type::getType(Type::STRING), ['length' => 255]),
                new Column('created_at', Type::getType(TYPE::DATETIMETZ_IMMUTABLE))
            ]
        );
    }
}
