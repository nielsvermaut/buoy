<?php

namespace Buoy\Service;

use Buoy\Factory\TableFactory;
use Buoy\Repository\BuoyRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;

/**
 * Class DatabaseService
 * @package Buoy\Service
 *
 * Runs the operations on the database
 */
class DatabaseService
{
    /** @var Connection|null */
    private $connection;

    /** @var BuoyRepository|null */
    private $repo;

    /**
     * Must be set before anything happens to the connection.
     *
     * @param Connection|null $connection
     *
     * @return DatabaseService
     */
    public function setConnection(Connection $connection): DatabaseService
    {
        $this->connection = $connection;
        $this->repo = new BuoyRepository($connection);
        return $this;
    }

    /**
     * @param string $databaseName
     *
     * @throws \Exception
     */
    public function createDatabase(string $databaseName): void
    {
        if ($this->connection === null) {
            throw new \Exception('The connection has to be created first, before calling this method.');
        }

        if (!$this->repo->isDatabaseServerPrimed()) {
            $this->primeDatabaseServer();
        }

        if (!$this->repo->isDatabasePrimed()) {
            $this->primeDatabase();
        }

        $this->connection->getSchemaManager()->dropAndCreateDatabase($databaseName);

        $this->storeDatabaseRecord($databaseName);
    }

    /**
     * @param string $databaseName
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function deleteDatabase(string $databaseName): void
    {
        if ($this->connection === null) {
            throw new \Exception('The connection has to be created first, before calling this method.');
        }

        if (!$this->repo->isDatabaseServerPrimed()) {
            $this->primeDatabaseServer();
        }

        if (!$this->repo->isDatabasePrimed()) {
            $this->primeDatabase();
        }

        $this->connection->getSchemaManager()->dropDatabase($databaseName);

        $this->deleteDatabaseRecord($databaseName);
    }

    /**
     * @param string $databaseName
     * @param string $owner
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function grantDatabaseToOwner(string $databaseName, string $owner): void
    {
        $this->connection->query('USE ' . $databaseName);

        $query = $this->connection->query(
            sprintf("GRANT ALL PRIVILEGES on `%s`.* to '%s'@'%s'", $databaseName, $owner, '%')
        );

        $query->bindValue('databaseName', $databaseName);
        $query->bindValue('owner', $owner);

        $query->execute();
    }

    /**
     * @param string $databaseName
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function storeDatabaseRecord(string $databaseName): void
    {
        $this->connection->query('USE buoy')->execute();

        $query = $this->connection->prepare('INSERT buoy_databases VALUES (:name, :createdAt)');

        $query->bindValue('name', $databaseName);
        $query->bindValue('createdAt', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

        $query->execute();
    }

    /**
     * @param string $databaseName
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function deleteDatabaseRecord(string $databaseName): void
    {
        $this->connection->query('USE buoy')->execute();

        $query = $this->connection->prepare('DELETE FROM buoy_databases WHERE name = :name');

        $query->bindValue('name', $databaseName);

        $query->execute();
    }

    /**
     * Creates the database for buoy to store metadata in.
     *
     * @return void
     *
     * @throws \Exception
     */
    private function primeDatabaseServer(): void
    {
        try {
            $this->connection->getSchemaManager()->dropAndCreateDatabase(BuoyRepository::BUOY_DATABASE_NAME);
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function primeDatabase(): void
    {
        $this->connection->getSchemaManager()->createTable(TableFactory::createBuoyDatabasesTable());
    }
}