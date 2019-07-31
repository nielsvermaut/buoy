<?php

namespace Buoy\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;

class BuoyRepository
{
    public const BUOY_DATABASE_NAME = 'buoy';

    /** @var Connection  */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return bool
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function isDatabasePrimed(): bool
    {
        $this->connection->query('USE buoy')->execute();

        $tables = array_map(static function (Table $table) {
            return $table->getName();
        }, $this->connection->getSchemaManager()->listTables());

        return in_array('buoy_databases', $tables, true);
    }

    /**
     * A database server is considered primed when the buoy table is present. This table is stored to see how old a
     * table is. When a table is older than an arbitrary number, it will be removed.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function isDatabaseServerPrimed(): bool
    {
        return in_array(
            self::BUOY_DATABASE_NAME,
            $this->connection->getSchemaManager()->listDatabases()
        );
    }
}
